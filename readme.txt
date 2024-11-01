=== Plugin Name ===
Contributors: takumin,FirstElement
Donate link: http://firstelement.jp/
Tags: feed,RSS,AJAX
Requires at least: 2.6
Tested up to: 3.2.1
Stable tag: 1.1 beta

You can add a Feed very easily.

== Description ==

This plug-in allows you to easily subscribe to the feed using the short code.
Display does not stop while getting feed by AJAX.
(Requires jQuery)

このプラグインは、ショートコードで簡単にフィードを読み込む事ができます。
AJAXにより、フィードの取得に時間がかかってもページのロードを妨げません。
使用するにはjQueryを有効にする必要が有ります。

ショートコードのオプション
[ AFR url='' limit='' ]
url:フェードのURL。複数有る場合はカンマ区切り
limit:表示するタイトルの数

== Installation ==

1. Upload `ajax-feed-reader` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3.Write [AFR url='http:example.com/feed' limit='3'] short code where you want to see the slideshow.

== Frequently Asked Questions ==

=An error that "Fatal error: Cannot redeclare class simplepie in" will be displayed.=

Please make sure there is no other thing you're using SimplePie.

== Screenshots ==
1.Write a short code.
2.This image was installed in the widget.


== Changelog ==

= 1.0 beta =
First release.

= 1.1 beta =
Modify the phrase

== Upgrade Notice ==

= 1.0 beta =
First release.

= 1.1 beta =
Modify the phrase


== Arbitrary section ==

Option of short code.

[ AFR url='' limit='' ]
url:Feed URL,separated by a comma.
limit:Number of titles to display.
