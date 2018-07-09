# Symfony Deployment Notifications

Send email notifications to one or more recipients using a simple console command 
that can be executed by your deployment process.

The notification can be sent to one or more recipients, and can optionally include 
the application change log file in the email content.

## Installation

Install with Composer

`composer require headsnet/deployment-notification-bundle`

Add bundle to `AppKernel.php`

```
public function registerBundles()
{
    $bundles = array(
        // ...
        new Headsnet\DeployNotifyBundle\HeadsnetDeployNotifyBundle(),
    );
}
```

## Configuration

Add required configuration in `app/config.yml`

```
headsnet_deploy_notify:
  app_name: My Application
  email:
    sender_email: app@domain.com
  recipients:
    - { name: Joe Bloggs, email: joe@email.com }
    - { name: John Smith, email: john@example.com }
```

You can optionally include a change log file in the email template. 
Just specify the name of the change log file:

```
headsnet_deploy_notify:
  changelog:
    filename: CHANGES.md
```

Markdown files ending with a `.md` extension will automatically be parsed in to HTML.

You can also override the mailer used to send the mails with your own service.

```
headsnet_deploy_notify:
  mailer: swiftmailer.mailer.my_mailer
```

## Usage

Simply execute the console command from your chosen deployment system.

`bin/console headsnet:deploy:notify`

## Configuration Reference

```
headsnet_deploy_notify:
	app_name:                 ~  # Required
	mailer:                   swiftmailer.mailer.default
	email:
	    sender_email:         ~  # Required
	    sender_name:          '' # Optional
	    subject:              'Deployment Notification'
	changelog:
	    filename:             ~  # If specified, changelog contents will be included in email
	    path:                 /  # Default is project root dir
	    public_url:           ~  # Optionally add link to changelog in email
	recipients:
	    - { name: ~, email: ~ }
	    - { name: ~, email: ~ }

```

## Contributing

Contributions are welcome. Please submit via pull requests. 
