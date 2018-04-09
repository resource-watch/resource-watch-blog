<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
?>
<div>
    <div id="icon-options-general" class="icon_ai">
      <br>
    </div><h2 id="jqh">
      <?php _e('Small jQuery help', 'advanced-iframe'); ?></h2>
      <p>
     <?php _e('You can use jquery selector patterns directly to identify the elements you want to modify at some of the settings. This plugin does use this selectors than at the right place. This is already an advanced topic if you are not familiar with jQuery.', 'advanced-iframe') ?>
      </p>
<?php if (true) {  ?>
    <p>
    <a href="#" onclick="jQuery('#jquery-help').show(); return false;" > <?php _e('Show me a small jQuery selector help.', 'advanced-iframe') ?></a>
    </p>
      <?php
      _e('<div id="jquery-help">

      <p>
      I have created a small jquery selector help which is optimized for the advanced iframes scenarios. It is an extract from http://refcardz.dzone.com/refcardz/jquery-selectors#refcard-download-social-buttons-display. So please go there if you need an extended version or give someone credit.
      </p>

      <h3>What are jQuery selectors?</h3>
      <p>
      jQuery selectors are one of the most important aspects of the jQuery library. These selectors use familiar CSS syntax to allow page authors to quickly and easily identify any set of page elements to operate upon with the jQuery library methods. Understanding jQuery selectors is the key to using the jQuery library most effectively. The selector is a string expression that identifies the set of DOM elements that will be collected into a matched set to be operated upon by the jQuery methods.
      </p>
           <h3>Types of jQuery selectors?</h3>
      <p>
        There are three categories of jQuery selectors: Basic CSS selectors, Positional selectors, and Custom jQuery selectors.
      </p><p>
The Basic Selectors are known as "find selectors" as they are used to find elements within the DOM. The Positional and Custom Selectors are "filter selectors" as they filter a set of elements (which defaults to the entire set of elements in the DOM). This extract will focus on the basic selectors as they are most important and will cover most of your needs.
      </p>

      <h4>Basic CSS Selectors</h4>
      <p>These selectors follow standard CSS3 syntax and semantics. For more selectors and examples go to <a href="http://api.jquery.com/category/selectors" target="_blank">http://api.jquery.com//category/selectors</a>.</p>
       <table cellspacing="0" cellpadding="0">
  			<thead>
  				<tr>
  					<th class="left_th_colored">Syntax</th>
  					<th class="right_th_colored">Description</th>
  				</tr>
  			</thead>
  			<tbody>
  				<tr>
  					<td class="left_td_colored">*</td>
  					<td class="right_td_colored">Matches any element.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E</td>
  					<td class="right_td_colored">Matches all elements with tag name E.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E F</td>
  					<td class="right_td_colored">Matches all elements with tag name F that are descendants of E.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E&gt;F</td>
  					<td class="right_td_colored">Use E##F. ## is converted to &gt;. Matches all elements with tag name F that are direct children of E.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E+F</td>
  					<td class="right_td_colored">Matches all elements with tag name F that are immediately preceded by a sibling of tag name E.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E~F</td>
  					<td class="right_td_colored">Matches all elements with tag name F that are preceded
by any sibling of tag name E.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E.c</td>
  					<td class="right_td_colored">Matches all elements E that possess a class name of c.
Omitting E is identical to *.c.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E#i</td>
  					<td class="right_td_colored">Matches all elements E that possess an id value of i.
Omitting E is identical to *#i.</td>
  				</tr>
					<tr>
  					<td class="left_td_colored">E[a]</td>
  					<td class="right_td_colored">Matches all elements E that posses an attribute a of any value.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E[a=v]</td>
  					<td class="right_td_colored">Matches all elements E that posses an attribute a whose value is exactly v.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E[a^=v]</td>
  					<td class="right_td_colored">Matches all elements E that posses an attribute a whose value starts with v.</td>
  				</tr>
					<tr>
  					<td class="left_td_colored">E[a$=v]</td>
  					<td class="right_td_colored">Matches all elements E that posses an attribute a whose value ends with v.</td>
  				</tr>
  				<tr>
  					<td class="left_td_colored">E[a*=v]</td>
  					<td class="right_td_colored">Matches all elements E that posses an attribute a whose value contains v.</td>
  				</tr>
  				
				</tbody>
				</table>
        
        <h4>Additional useful selectors</h4>
      <p>These selectors are basic filters provided by jQuery I found useful using in this plugin. For more selectors and examples go to <a href="http://api.jquery.com/category/selectors" target="_blank">http://api.jquery.com//category/selectors</a>.</p>
       <table cellspacing="0" cellpadding="0">
  			<thead>
        <tr>
  					<td class="left_td_colored">E:not(selector)</td>
  					<td class="right_td_colored">Remove elements from the set of matched elements.</td>
  				</tr>
          <tr>
  					<td class="left_td_colored">E:eq(index)</td>
  					<td class="right_td_colored">Select the element at index n within the matched set.</td>
  				</tr>
           <tr>
  					<td class="left_td_colored">E:last()</td>
  					<td class="right_td_colored">Selects the last matched element.</td>
  				</tr>
           <tr>
  					<td class="left_td_colored">E:nth-child(index)</td>
  					<td class="right_td_colored">Selects all elements that are the nth-child of their parent.</td>
  				</tr>
        	</tbody>
				</table>
        
        <h4>Examples</h4>
        <ul>
				<li>$("div") selects all &lt;div&gt; elements</li>
				<li>$("fieldset a") selects all &lt;a&gt; elements within &lt;fieldset&gt; elements</li>
				<li>$("li##p") selects all &lt;p&gt; elements that are direct children of &lt;li&gt; elements</li>
				<li>$("div~p") selects all &lt;div&gt; elements that are preceded by a &lt;p&gt; element</li>
				<li>$("p:has(b)") selects all &lt;p&gt; elements that contain a &lt;b&gt; element</li>
				<li>$("div.someClass") selects all &lt;div&gt; elements with a class name of someClass</li>
				<li>$(".someClass") selects all elements with class name someClass</li>
				<li>$("#testButton") selects the element with the id value of testButton</li>
				<li>$("img[alt]") selects all &lt;img&gt; elements that possess an alt attribute</li>
				<li>$("a[href$=.pdf]") selects all &lt;a&gt; elements that possess an href attribute that ends in .pdf</li>
				<li>$("button[id*=test]") selects all buttons whose id attributes contain test</li>
        <li>$("tr:not(.keep)") selects all table row that don\'t have the class "keep"</li>
        <li>$("table:nth-child(1)") selects the 2nd row of a table</li>

				</ul>
        <p>You can create the union of multiple disparate selectors by listing them, separated by commas. For example, the following matches all &lt;div&gt; and &lt;p&gt; elements: div,p</p>
      </div>', 'advanced-iframe');
} else {
      _e('<p>Please go to the jQuery API <a target="_blank" href="http://api.jquery.com/category/selectors/">http://api.jquery.com/category/selectors/</a> for the official documentation.
          </p>
          <p>
          The <strong>advanced iframe pro</strong> version has an included jQuery help with examples.
          </p>
          ', 'advanced-iframe');
     }
      ?>
</div>