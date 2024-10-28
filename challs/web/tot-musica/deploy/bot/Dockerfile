FROM node:current-alpine

ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser

RUN apk add --no-cache chromium \
    nss \
    curl \
    freetype \
    harfbuzz \
    ca-certificates \
    ttf-freefont && \
    adduser -h /app -D puppeteer
    
COPY ./ /app/
    
WORKDIR /app

RUN npm i

USER puppeteer

CMD [ "node", "app.js" ]