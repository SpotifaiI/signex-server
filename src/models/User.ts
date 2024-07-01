import Connection from '../db/Connection.ts';

export default class User {
  async about() {
    const connection = await Connection.getInstance();

    connection.run('SELECT name FROM sqlite_master WHERE type=\'table\'').then((result) => {
      console.log(result);
    });
  }
}
