<?php

    namespace Signex\Methods;

    use Exception;
    use Signex\Data\Mail;
    use Signex\Data\Sign as SignModel;
    use Signex\Lib\Dotenv;
    use Signex\Lib\File;
    use Signex\Lib\Response;

    class Sign extends Method {
        private SignModel $sign;

        public function __construct(array $params) {
            parent::__construct($params);

            $this->sign = new SignModel();
        }

        public function list(): Response {
            try {
                $userId = $this->params[0] ?? null;

                if (empty($userId)) {
                    throw new Exception(
                        'Parâmetro de ID do usuário faltando na URL.'
                    );
                }

                $this->validate(['token']);
                $this->authenticate($this->body['token'], $userId);

                $signs = $this->sign->getByUser($userId);

                $this->response->setOk(true)
                    ->setMessage(sprintf(
                        "Assinaturas do usuário %d listadas com sucesso.",
                        $userId
                    ))
                    ->setData($signs);
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }

        public function add(): Response {
            try {
                $userId = $this->params[0] ?? null;

                if (empty($userId)) {
                    throw new Exception(
                        'Parâmetro de ID do usuário faltando na URL.'
                    );
                }

                $this->validate(['token', 'emails']);
                $this->authenticate($this->body['token'], $userId);

                $emails = json_decode($this->body['emails'], true);
                $emails = array_map(function ($email) {
                    return $email['email'];
                }, $emails);

                $upload = File::upload($this->files['file']);
                $newFilePath = sprintf(
                    "%s.%s",
                    $upload->name, $upload->extension
                );
                $content = file_get_contents($upload->path);

                file_put_contents(SIGNEX_ROOT.'/public/'.$newFilePath, $content);

                $signId = $this->sign->add($userId, [
                    'content' => $content,
                    'file' => $newFilePath
                ]);
                if (!empty($signId)) {
                    $signers = $this->sign->sign($signId, $emails);

                    $this->sendAlerts($signers);
                }

                $this->response->setOk(true)
                    ->setMessage('Assinatura criada com sucesso.')
                    ->setData([$signId => $signers]);
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }

        /**
         * @param int[] $signersIds
         */
        private function sendAlerts(array $signersIds): void {
            $mail = new Mail();
            $mailInfoList = $this->sign->getToSign($signersIds);
            $headers = "From: sign@signex.com" . "\r\n";

            foreach ($mailInfoList as $mailInfo) {
                $signUrl = sprintf(
                    "%s/sign/%s/%d",
                    Dotenv::get('WEBVIEW_ENDPOINT'),
                    urlencode($mailInfo['hash']),
                    $mailInfo['signer']
                );
                $message = '<a href="'.$signUrl.'">Clique aqui</a> '.
                    'para assinar o documento usando o código '.
                    '<b>'.$mailInfo['code'].'</b>';

                $mail->add([
                    'email' => $mailInfo['email'],
                    'message' => $message
                ]);
                mail(
                    $mailInfo['email'],
                    'Assinatura de documento',
                    $message,
                    $headers
                );
            }
        }
        
        public function remove(): Response {
            try {
                $signId = $this->params[0] ?? null;

                if (empty($signId)) {
                    throw new Exception(
                        'Parâmetro de ID da assinatura faltando na URL.'
                    );
                }

                $this->validate(['token', 'user']);
                $this->authenticate($this->body['token'], $this->body['user']);
                
                $this->sign->delete((int) $signId);

                $this->response->setOk(true)
                    ->setMessage('Assinatura removida com sucesso.');
            } catch (Exception $exception) {
                $this->response->setOk(false)
                    ->setMessage($exception->getMessage());
            } finally {
                return $this->response;
            }
        }
    }
