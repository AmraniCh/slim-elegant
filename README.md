# slim-elegant

💠 Simple Slim skeleton that supports Blade templating, Eloquent Models, and other features.

## Features

* Flexible and Simple (Built with KIS Concept in Mind).
* Blade Templating.
* Eloquent Models.
* Whoops Error Handler.
* CRSF Protection.
* Supporting environment variables.
* Configurable PHP Sessions.
* Ships with ready-to-use HTTP response objects.

## Installation

Create the project using this composer command:

```bash
composer create-project amranich/slim-elegant app-name --stability dev
```

Configure git (if you use a VCS):

```
git remote add origin URL
git add .
git commit -m "First commit"
git push -u origin master
```

## TODO

* Support Flash Messages.
* Support backend validation of requests parameters (may be integrate the `illuminate\Validation` component).
* Adding more helper functions (`redirect`, `redirectToRoute`, `assets` ...).
* Support per request crsf tokens (Non persistence mode).
* Adding user authentication layer.
* Support console requests.
* Provide a simple CLI interface and .