FROM node:lts-bookworm-slim

COPY ../client/public /client/public
COPY ../client/src /client/src
COPY ../client/index.html /client/index.html
COPY ../client/jsconfig.json /client/jsconfig.json
COPY ../client/package.json /client/package.json
COPY ../client/package-lock.json /client/package-lock.json
COPY ../client/vite.config.js /client/vite.config.js

WORKDIR /client

RUN npm install

CMD ["npm", "run", "dev"]