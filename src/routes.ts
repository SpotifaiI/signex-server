import { Router } from 'express';

import UserController from './controllers/UserController.ts';

const routes = Router();

const userController = new UserController();

routes.get('/user/about', userController.create.bind(userController));

export default routes;
