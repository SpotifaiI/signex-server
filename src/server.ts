import express from 'express';

import { routes } from './routes.ts';
import { sequelize } from './db/db.ts';

const app = express();

app.use(express.json());
app.use(routes)

export async function bootstrap() {
  await sequelize.sync();
  app.listen(3000);
}
