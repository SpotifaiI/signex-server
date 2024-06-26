import { Router } from 'express';

import { UserController } from './controllers/user.ts';

const routes = Router();

const userController = new UserController();

routes.get('/', userController.list);

export { routes };
