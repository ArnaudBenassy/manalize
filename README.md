Manala
======

[![Build Status](https://travis-ci.org/manala/manala.svg?branch=master)](https://travis-ci.org/manala/manala)

This project helps to manage vagrant environments for PHP projects through the CLI.

#### /!\ This project is still in progress, use it carefully.

Installation
-------------

#### Download:
```sh
$ curl -L https://github.com/manala/manala/releases/download/v0.1.1/manala.phar > /usr/local/bin/manala
$ chmod +x /usr/local/bin/manala
```

#### Manual build:
```sh
$ git clone git@github.com:manala/manala
$ make build
$ mv manala.phar /usr/local/bin/manala
$ chmod +x /usr/local/bin/manala
```

Usage
-----

#### Setup
```
$ manala setup ~/my-awesome-project
```

At this point, your virtual machine is configured and ready to be provisionned.

#### Build
```
$ manala build ~/my-awesome-project
```

Your vagrant box is up and provisionned through [manala ansible roles](https://github.com/manala?query=ansible-role).  
Now, use the [vagrant CLI tools](https://www.vagrantup.com/docs/cli/) to manage it.

License
-------

This project is licensed under MIT.  
For the whole copyright, see the [LICENSE](LICENSE) file distributed with this source code.
