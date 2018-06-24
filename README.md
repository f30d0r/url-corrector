# URL Corrector for [GetSimple CMS](https://github.com/GetSimpleCMS/GetSimpleCMS)

GetSimple is a very good CMS, but it has one very big problem. It doesn't correct incorrect urls.
For example you have a page, and you can get access to it by four different ways.

1. You can get it by GET variable `http://site.com/index.php?id=page`
2. You can get it by semantic URL `http://site.com/page/`
3. You can get it with parent in URL `http://site.com/parent/page/`
3. And you also can get it with incorrect parent `http://site.com/fakeparent/page/`

It's unacceptable for search optimization, because for search engines it's looks like different pages with same content.

URL Corrector plugin created to solve this problem. If fancy URLs are enabled in settings menu, plugin correct any incorrect URL by pattern, that defined in settings menu. By default it's `%parent%/%slug%/`

### Changelog
**2.0**  
* Refactoring. All known bugs fixed. Now plugin compatible with PHP 7.

**1.2**  
* Fixed a bug with the double slash.  

**1.1**  
* Fix for problem with GS installation in folder on domain.
