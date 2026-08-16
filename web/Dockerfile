# Build de produccion — este es el Dockerfile que build & push usan en deploy-apps.yml,
# distinto de Dockerfile.dev (vite dev server, usado por service/docker-compose.yml para
# desarrollo local con hot-reload). container_apps.tf espera que el frontend sirva en el
# puerto 80 via un servidor estatico, no un dev server.
FROM node:20-alpine AS build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .

# Vite bakea las VITE_* en el bundle en tiempo de build, no las lee en runtime — por eso
# deploy-apps.yml pasa estas URLs como --build-arg (resueltas desde las Container Apps ya
# desplegadas de api/ai-service), no como env vars del contenedor de nginx.
ARG VITE_API_BASE_URL
ARG VITE_DOCUMENT_API_BASE_URL
ENV VITE_API_BASE_URL=$VITE_API_BASE_URL
ENV VITE_DOCUMENT_API_BASE_URL=$VITE_DOCUMENT_API_BASE_URL
RUN npm run build

FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
