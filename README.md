# PHP CLI demo app

This application is part of a brown bag talk given on April 27, 2017 to showcase the [webmozart/console](https://github.com/webmozart/console) library for building beautiful PHP console applications without investing a lot of time in boiler place code.

The app allows the user to fetch reviews from the yotpo api. In order to test it out do the following:

* Copy the `.config.dist` to `.config` and add the api key and secret
* In the project root run `composer install` (requires Composer to be globally installed)
* In the project root run `./ycli` to see the help information

### Tip

Since the app returns json it is possible to pipe the output in to [jq](https://stedolan.github.io/jq/) for further processing.

For example:
```
./ycli r GHF-0001 -p 1 -c 2 | jq .
```
