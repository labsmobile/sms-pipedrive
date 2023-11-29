<p align="center">
  <img src="https://avatars.githubusercontent.com/u/152215067?s=200&v=4" height="80">
</p>

# LabsMobile-Pipedrive

![](https://img.shields.io/badge/version-1.0.0-blue.svg)
 
Send SMS messages through the LabsMobile platform and the Pipedrive CRM plugin. Configure your Pipedrive user so that you can send SMS messages to any contact and notify events, meetings, appointments, organization status, quotes, among others.

## Documentation

Labsmobile API documentation can be found [here][apidocs].

## Features

  - SMS notification to contacts

## Requirements

- A user in Pipedrive. More information in [pipedrive.com][pipedrive].
- A script that receives Pipedrive (Webhook) events and communicates with the LabsMobile API. More information in [API SMS de LabsMobil][apidocs].
- A user account with LabsMobile. Click on the link to create an account [here][signUp].

## Installation

1. Download script, this script must be hosted in a web service and thus obtain a URL where it will be publicly accessible.

  - Within the script you must update the pipedrive token which can be found within your user profile.
  - Add the credentials of the LabsMobile account. That is, the username email and the API token that must be generated in the Security and passwords section of the LabsMobile account.

2. Create SMS activity. To create the activity, follow the following route: Settings/Company settings/Add type of activity. You must indicate “SMS” as the name of the activity and also select the “Phone” icon. Finally Save.

3. Create webhook in Pipedrive. Go to the path Tools and applications/Webhook. There we create a new Webhook and indicate that an event is sent to the URL of the script from step 1 when an activity is created.

4. Enable link shortening in the Preferences section of your LabsMobile account. In this way, any ticket link will be shortened to the minimum possible to optimize the characters present in the text of the SMS.

5. Test the SMS integration with Pipedrive by creating an SMS activity.

In this case, the LabsMobile API is used, in order to notify via SMS when the Pipedrive user wants to send an SMS type message to a contact. In this way it is possible to add the SMS channel to CRM Pipedrive.

## Help

If you have questions, you can contact us through the support chat or through the support email support@labsmobile.com.

[apidocs]: https://apidocs.labsmobile.com/
[signUp]: https://www.labsmobile.com/en/signup
[pipedrive]: https://www.pipedrive.com/es-es