# We-Movies

## System requirements

* docker
* docker-compose
* make

## Other requirements
You must create an account on [TMDB](https://www.themoviedb.org/) and get an **api_key** to use their api

## Install
To install the project, start by cloning it

```bach
> git clone git@github.com:ibezzina/we-movies.git
```

and then run

```bach
> TMDB_api_Key={your TMDB api_key} make install
```

## Code style, code analysis and tests

```bach
> make test
```
