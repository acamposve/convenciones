# Build de produccion (multi-stage + nginx en :80, sirve estatico) — el deploy que lo usaba
# (deploy-apps.yml + container_apps.tf) se retiro con Azure (Enmienda 2.3.0); sigue siendo
# el build de produccion del contenedor una vez que se elija el proveedor nuevo. Distinto de
# Dockerfile.dev (vite dev server, usado por docker-compose.yml para desarrollo local con
# hot-reload).
FROM node:20-alpine AS build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .

# Vite bakea las VITE_* en el bundle en tiempo de build, no las lee en runtime.
ARG VITE_API_BASE_URL
ENV VITE_API_BASE_URL=$VITE_API_BASE_URL
RUN npm run build

FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
