This is a simple viewer for Netatmo module stats for a permanent visible device.

![Sample dashboard](web/assets/dashboard.png)

Simply configure `app/config/prod.json` by overwriting it with your Netatmo credentials.

```
{
    "$extends": "local",
    "debug": false,
    "client_id": "your_client_id",
    "client_secret": "your_client_secret",
    "username": "your_username",
    "password": "your_password",
    "stationNames": ["your_station_name_1", "your_station_name_2"]
}

```

To start it locally simply run `COMPOSER_PROCESS_TIMEOUT=0 composer serve` and open the [Netatmo viewer](http://localhost:8080).
