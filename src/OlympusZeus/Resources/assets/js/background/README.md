# Ol Background

---

### Warning

This Ol plugin is a part of the Olympus Ol library.

---

### Use

````html
<!-- In your <body> HTML tag -->

<div class="background">
    <figure></figure>
    <fieldset>
        <label><input type="text" name="color" value="#000000" /></label>
        <select name="position">
            <option value="left top">Left top</option>
            <option value="center top">Center top</option>
            <option value="right top">Right top</option>
        </select>
        <p>
            <input type="radio" name="repeat" value="no-repeat"/> No repeat
            <input type="radio" name="repeat" value="repeat-x"/> Repeat horizontally
            <input type="radio" name="repeat" value="repeat-y"/> Repeat vertically
            <input type="radio" name="repeat" value="repeat"/> Repeat all the way around
        </p>
        <p>
            <input type="radio" name="size" value="auto"/> Default value
            <input type="radio" name="size" value="cover"/> As large as possible
            <input type="radio" name="size" value="contain"/> Width and height fit in the content area
        </p>
    </fieldset>
</div>
````

````javascript
//in your main JS file

$('.background').heraBackground({
    color: 'input[name="color"]',
    position: 'input[type="radio"]',
    preview: 'figure',
    repeat: 'select[name="position"]',
});
````

---

### Settings

Option | Type | Default | Description
------ | ---- | ------- | -----------
color | string | '.bg-color input' | Item node to set background color
position | string | '.bg-position input' | Item node to set background position
preview | string | '.bg-preview' | Item node containing backgroud preview
repeat | string | '.bg-size input' | Item node to set background repeat

---

### Dependencies

+ jQuery 2.1.4
+ Olympus Ol Color

---

### Authors and Copyright

**Achraf Chouk**

+ http://fr.linkedin.com/in/achrafchouk/
+ http://twitter.com/crewstyle
+ http://github.com/crewstyle

Please, read [LICENSE](https://github.com/crewstyle/OlympusOl/blob/master/LICENSE "LICENSE") ([MIT](http://opensource.org/licenses/MIT "MIT")) for more details.

---

**Built with ♥ by [Achraf Chouk](http://github.com/crewstyle "Achraf Chouk") ~ (c) since 2015.**
