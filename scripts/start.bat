@echo off
wt cmd /k "title Docker && cd /d Z:\Webserver\aktuell\allinkl\dogsitter.webwork-oberland.de && docker-compose up -d" ^
   ; new-tab cmd /k "title Backend && cd /d Z:\Webserver\aktuell\allinkl\dogsitter.webwork-oberland.de\backend && php artisan serve" ^
   ; new-tab cmd /k "title Frontend && cd /d Z:\Webserver\aktuell\allinkl\dogsitter.webwork-oberland.de\frontend && npm run dev"
