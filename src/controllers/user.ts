import { Request, Response } from 'express';

import { User } from '../models/user.ts';

const user = new User();

export class UserController {
  list(request: Request, response: Response) {
    return response.send({
      success: 'show'
    });
  }
}
