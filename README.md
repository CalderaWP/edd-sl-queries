# edd-sl-queries
Useful queries for Easy Digital Downloads Software Licensing.

<strong>Requires PHP 7.0 or later</strong>

These queries are also avaible via REST API using our [EDD Software Licensing Endpoints](https://github.com/CalderaWP/edd-sl-api) plugin.

## Install
```
  "repositories" : [
    {
      "type": "git",
      "url" : "https://github.com/CalderaWP/edd-sl-queries"
    }
  ],
  "require": {
    "php": ">=7.0.0",
    "CalderaWP/edd-sl-queries": "dev-master"
  }
  
```

## Usage

* URLs of all sites with an activated license:
```
  $sl = new \CalderaWP\EDD\SL\sites();
  $sites = $sl->get_all();
```

* URLs of all sites with an active license for a specific download
```
  $sl = new \CalderaWP\EDD\SL\sites();
  $sites = $sl->get_by_download(42);
```


* URLs of all sites with an active license for a specific user
```
  $sl = new \CalderaWP\EDD\SL\sites();
  $sites = $sl->get_user_sites(42);
```


### Copyright/ License
Copyright 2016 Josh Pollock & CalderaWP LLC. Licensed under the terms of the GNU GPL v2 or later.

