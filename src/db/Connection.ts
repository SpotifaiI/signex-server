import sqlite3 from 'sqlite3';
import { Database, open } from 'sqlite';

export default class Connection {
  private static db: Database|null;

  static async getInstance(): Promise<Database> {
    if (!Connection.db) {
      Connection.db = await open({
        filename: './src/db/db.sqlite',
        driver: sqlite3.Database
      });
    }

    return Connection.db;
  }
}
