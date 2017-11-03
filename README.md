[![Build Status](https://travis-ci.org/chrisdoherty4/backup-tool.svg?branch=master)](https://travis-ci.org/chrisdoherty4/backup-tool)

# Backup Tool

A simple backup tool used to create and store backups on different platforms. The program is used on a command line and typically invoked via a crontab. 

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

To contribute you require `composer` and `php version >=5.6`. Checkout the repository and run composer.

```
git clone https://github.com/chrisdoherty4/backup-tool.git backup-tool
cd backup-tool
composer install
```

### Installing

There are no specfic steps for getting a development environment setup. The system is a command line utility that is invoked using the `backup` executable. The system is built on top of [Cilex](https://github.com/Cilex/Cilex) with commands called the same way.

For the application to run, the `.env` file needs to be completed providing a configuration for cPanel. See [.env.example](.env.example) for details.

```
php backup cpanel
```

## Running the tests

We are yet to define tests - stay tuned.

## Deployment

1. Install via favourite method onto target.
1. Run `composer install`.
1. Copy and configure `.env.example`.
1. Set up a crontab to run as desired

Suggested crontab: 

```
0 0 * * * /directory/to/backup cpanel > "/var/logs/$(date '+\%Y\%m\%d')_backup_cpanel" 2>&1
```

## Contributing

Please read [CONTRIBUTING.md]() for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/chrisdoherty4/backup-tool/tags). 

## Authors

* **Chris Doherty <chris.doherty4@gmail.com> (http://chrisdoherty.io)** - *Initial work* - [Backup Tool](https://github.com/chrisdoherty4/backup-tool)

See also the list of [contributors](https://github.com/chrisdoherty4/backup-tool/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

