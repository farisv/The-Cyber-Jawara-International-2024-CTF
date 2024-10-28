FROM node:20.8.0-bookworm

WORKDIR /app

COPY app.js .
COPY index.html .
COPY package.json .

RUN npm install

CMD ["node", "app.js"]
