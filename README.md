# Navigation Function and Twig Filter Plugin for Craft CMS
One solution to handle all (nearly ;-)) navigation needs in Craft CMS.

## Features
Twig filters and functions for simple and advanced navigations in Craft CMS:
- get a unordered list navigation from a section
- add selected class to a unordered list navigation
- show different levels of a unordered list navigation
- show a breadcrumb of a unordered list navigation
- Multilanguage Support

## Installation
- Place the k4navigation folder inside your craft/plugins folder.
- Install the plugin via the Craft Dashboard. (Settings -> Plugins).
- Add the navigation function and twig filter to your templates (see examples below)

## How to use: the basic Navigation
The k4-naviation Plugin includes a simple function to output a unordered list navigation from any Craft section. 

    {{ craft.k4Navigation.getNavigation("yourSectionHandle") | raw }}

Output a unordered list navigation or your section.

## How to use: Examples of twig filters

The k4-naviation Plugin provides different twig filters to create powerful navigations from a unordered list (see advanced usage). In the following examples we use the basic navigation from the plugin. The filters can be used on any unordered list navigation. 


### Navigation with "active" class 

Simple add class "active" to the current link and all parent li elements. Outputs the complete navigation.

    {{ craft.k4Navigation.getNavigation("yourSectionHandle") | k4NavigationGetActivePath(craft.request.getUrl()) | raw }}
    
Note: Instead of using craft.request.getUrl() you can also use your own "selected path". That's usefull, if you like to select a navigation entry although you'r in a other section (for example in a news channel).
    

### Navigation incl. next sublevel
Simple add class "active" to the current link and all parent li elements. Outputs only the current path including the next sublevel.

    {{ craft.k4Navigation.getNavigation("yourSectionHandle") | k4NavigationGetSimpleNavigation(craft.request.getUrl()) | raw }} 


### Navigation from level x to level x
Simple add class "active" to the current link and all parent li elements. Outputs only the selected levels of a navigation.

     {{ craft.k4Navigation.getNavigation("yourSectionHandle") | k4NavigationGetSimpleNavigation(craft.request.getUrl())| k4NavigationShowFromTo(1,2) | raw }}

Note: Change the Levels in the parameter of the filter: k4NavigationShowFromTo(fromLevel,toLevel). 

### Get a Breadcrumb
Outputs the breadcrumb of the current path

    {{ craft.k4Navigation.getNavigation("yourSectionHandle") | k4NavigationGetBreadcrumb(craft.request.getUrl()," > ") | raw }} 


## Advanced Usage

### Performance
It is recommended to cache the basic navigation. With this solution it's also easy to use the navigation several times in your template without any performance issues. The usage could look like this:

    {% set navigation %}
        {% cache globally for 3 years %}
            {{ craft.k4Navigation.getNavigation("yourSectionHandle") }} 
        {% endcache %}
    {% endset %}
    {{ navigation  | k4NavigationGetActivePath(craft.request.getUrl()) | raw }} 
    {{ navigation  | k4NavigationGetBreadcrumb(craft.request.getUrl()," > ") | raw }} 

### Custom Navigations

One great thing is, that you can use the twig filters with your own navigations. Below is a sample code that provides the possibility of including other sections as links in the navigation. You can include this code direct in your template and change as needed. 

    {% macro recursive_nav(entries, depth, wrapUl) %}
    {% import _self as self %}
    {% if wrapUl %}
      <ul class="level{{ depth}}">
    {% endif %}
        {% for entry in entries %}
            {% if entry.type == "sectionLink" %}
            {% set firstSectionLink = craft.entries.section(entry.sektion).first() %}
                {% if  firstSectionLink.section.type == "single" %}
                <li><a href="{{ firstSectionLink.url }}">{{ entry.title }}</a></li>
                {% else %}
                    {% set entries2 = craft.entries.section(entry.sektion).level(1) %}
                    {{ self.recursive_nav(entries2, depth, false) }} 
                {% endif %}
            {% else %}
              <li>
                <a href="{{ entry.url }}">{{ entry.title }}</a>
                {% if entry.hasDescendants %}
                  {{ self.recursive_nav(entry.children, depth+1, true) }}
                {% endif %}
              </li>			
            {% endif %}
        {% endfor %}
    {% if wrapUl %}
      </ul> 
    {% endif %}
    {% endmacro %} 

    {% import _self as self %}

    {% set navigation %}
        {% cache globally for 3 years %}

            {% set navEntries = craft.entries.section("sectionHandle").level(1) %}
            <ul class="level1">
                {{ self.recursive_nav(navEntries, 1, false) }}
            </ul>
        {% endcache %}
    {% endset %}
    {{ navigation  | k4NavigationGetActivePath(craft.request.getUrl()) | raw }} 

## Notes
- This plugin is inspired from the idea of this post: http://craftcms.stackexchange.com/questions/1473/would-you-use-a-structure-as-navigation-over-multiple-sections-channel-entries
- This plugin uses the PHP Simple HTML DOM Parser -> http://simplehtmldom.sourceforge.net

## Disclaimer & support

k4-navigation is provided free of charge. Report any bugs, feature requests or other issues here on GitHub. As k4-navigation is a free plugin, no promises are made regarding response time, feature implementations or bug amendments.

### Changelog
1.0 (1.12.2015):
- Updated Documentation
- Bugfixing
- Add functions
- Cleanup Code

0.1 (16.4.2015):
- Initial release
