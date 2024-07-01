import express from 'express';
import cors from 'cors';

import routes from './routes.ts';

const app = express();

app.use(cors());
app.use(express.json());
app.use(routes)

export async function bootstrap() {
  app.listen(3000);
}
