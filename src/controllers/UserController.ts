import User from '../models/User.ts';

export default class UserController {
  private user: User;

  public constructor() {
    this.user = new User();
  }

  public async create() {
    await this.user.about();
  }
}
