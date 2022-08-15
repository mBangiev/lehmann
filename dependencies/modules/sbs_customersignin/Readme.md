# Sbs_CustomerSignIn 1.0.4

To display the User block link in the mobile menu:
- Add (in two different places)
```
<div id="_mobile_user_info"></div>
```
after 
```
<div id="_mobile_contact_link"></div>
```
in the path `themes/YOUR_THEME/templates/_partials/header.tpl:85` and here `themes/YOUR_THEME/templates/checkout/_partials/header.tpl:69`.
- Disable line with id = `_mobile_user_info` in `themes/classic/templates/_partials/header.tpl`.

## Changelog
#### 1.0.4 (30.03.2021)
* Fixed translations in the template

#### 1.0.3 (23.03.2021)
* Fixed show/hide the desktop user menu when customer is logged

#### 1.0.2 (02.09.2020)
* Fixed show/hide the desktop user menu

#### 1.0.1 (07.07.2020)
* Changed place to show the point of menu in mobile block

#### 1.0.0 (05.05.2018)
* Init
