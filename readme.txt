=== Aidanto – AI Customer Service Chat ===
Contributors: aidanto
Tags: chat, live chat, ai chatbot, customer service, appointment booking
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add the Aidanto website chat: an AI employee that answers your customers, books appointments and hands the conversation to you.

== Description ==

Aidanto is an AI employee that answers your customers on WhatsApp, Instagram, Messenger, Telegram, email and your website chat. It learns your services and prices, books appointments and hands the conversation to you when a person is needed.

This plugin adds the Aidanto website chat to your WordPress site. Paste the code from Aidanto on one settings page, and a chat button appears in the bottom right corner of every public page. No theme editing is needed.

What the AI employee does in the chat:

* Answers visitors from your own information: your services, prices and documents.
* Checks free times, requests the slot and asks you to confirm or decline with one tap.
* Answers in the customer's language.
* Hands the conversation to your shared inbox with its full history when a customer asks for a person or one of your rules says so.

Aidanto works with written conversations. It does not answer phone calls.

= What you need =

An Aidanto account. There is a free 14-day trial with no card needed; plans start at $29 a month. Create an account at [aidanto.com](https://aidanto.com/).

= What the plugin does on your site =

* Adds one settings page: Settings → Aidanto.
* Stores one option: your site ID and the on/off switch.
* Prints one script tag on public pages. Nothing is added to the WordPress admin area.
* Removes its option when you delete the plugin.

== Installation ==

1. Install the plugin and activate it.
2. In Aidanto, open “AI employees”, choose an employee and go to the “Channels” tab. In the “Web chat” block, click “Turn on web chat”.
3. In the “Widget on your website” card, enter the address of your WordPress site in “Your website URL” and click “Prepare code”. The address must start with https.
4. Click “Copy code”.
5. In WordPress, open Settings → Aidanto, paste the code and click “Save Changes”. You can paste the whole code or only the site ID that starts with `site_`.
6. Open your site. A chat button appears in the bottom right corner. Click it, ask a question and make sure the answer arrives.

The chat opens only on the website address you entered in Aidanto, with or without “www”.

== Frequently Asked Questions ==

= Do I need an Aidanto account? =

Yes. The chat and the AI employee run on Aidanto. You can try it free for 14 days with no card needed; plans start at $29 a month.

= Where do I find the code? =

In Aidanto, open “AI employees”, choose an employee and go to the “Channels” tab. The code is in the “Widget on your website” card of the “Web chat” block. The Settings → Aidanto page in WordPress shows the same steps and the address to enter.

= The chat button appears, but the chat window stays empty. Why? =

The chat opens only on the website address entered in Aidanto. Check “Your website URL” in the “Widget on your website” card: it must be the address of this WordPress site and start with https. An address with “www” and the same address without it count as one site; any other subdomain is a different site. After you change the address, click “Prepare code” again.

= Does it work on a local or staging copy of my site? =

Not with the main code. In Aidanto, open “More settings” in the “Web chat” block and prepare the test code: it works on localhost and on the test addresses you list. Paste the test code into the plugin on that copy. Conversations from the test code are marked “Test” in your inbox.

= I clicked “New website code” in Aidanto and the chat stopped working. =

A new code replaces the old one. Copy the new code and paste it in Settings → Aidanto.

= How do I hide the chat for a while? =

Clear “Show the chat on this site” in Settings → Aidanto and save. The code stays saved.

= Does the plugin use cookies? =

No. The plugin and the chat set no cookies. The chat keeps a few values in the browser's local storage; see “External services” below.

= Can I change how the chat looks? =

Yes, in Aidanto: the “Appearance” section of the “Web chat” block sets the main color and the theme. You do not need to change anything in WordPress.

= Does Aidanto answer phone calls? =

No. Aidanto answers written conversations: WhatsApp, Instagram, Messenger, Telegram, email and website chat.

== External services ==

This plugin connects your site to Aidanto, a service operated by CIPHRA SOFTWARE INC. The chat runs on Aidanto servers, so the plugin cannot work without this service.

= What is loaded, and when =

While the chat is switched on and a site ID is saved, every public page of your site loads the script `https://channels.aidanto.com/embed/v1.js` in the visitor's browser. Nothing is loaded in the WordPress admin area. The plugin code on your server never contacts Aidanto.

= What is sent, and when =

* On every public page view, the visitor's browser requests the script and the look of the chat button from `channels.aidanto.com`. Like any web request, these carry the visitor's IP address, browser type and the address of the site the request came from, together with your site ID. No cookies are sent or set.
* When a visitor opens the chat, the chat window is loaded from `channels.aidanto.com` in a frame. The messages the visitor writes, the images they attach and the contact details they choose to leave are sent to Aidanto, processed by AI to prepare an answer, and shown to you in your Aidanto inbox.
* When a visitor starts a conversation, the chat stores random identifiers in the browser's local storage. They let a returning visitor continue the conversation and let you block a visitor. They are not cookies and are not used for advertising.
* Aidanto counts anonymous usage statistics: the chat was shown or opened, conversations and messages, with the visitor's country and device type. No names, contact details, message text, IP addresses or page addresses are included. You can turn these statistics off in the web chat settings in Aidanto.

The plugin sends no information about your WordPress users, content or settings.

= Terms and privacy =

* [Aidanto Terms of Service](https://aidanto.com/terms)
* [Aidanto Privacy Policy](https://aidanto.com/privacy)

== Changelog ==

= 1.0.0 =
* First release: settings page for the website code or site ID, an on/off switch, and the chat on public pages.
