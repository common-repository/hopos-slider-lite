<style>
.widefat th { font-family:Arial, Helvetica, sans-serif; font-size:12px; }
</style>
<div class="wrap" style="padding:0px; margin-top:20px;">

	<h2><?php _e('Hopos Slider - Css Customization', 'hopos-plugin') ?></h2>
    
    <div id="left" style="float:left; margin-right:30px; width:45%">
    
        <p>Below are all the css classes available for customization. Replace the word CUSTOM by the main class of the Skin</p>
    
        <table class="form-table">
        
            <tr valign="top" class="widefat alternate" style="border-top:#666 solid 2px;">
                <th><strong>CSS Class</strong></th>
                <th><strong>Description</strong></th>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_container.CUSTOM {}</th>
                <td>
                    This class involves all of the slider
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_header.CUSTOM {}</th>
                <td>
                    The header slider. Some Skins do not wear the header element</td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_item.CUSTOM {}</th>
                <td>
                    Each item in the slider
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_thumb.CUSTOM {}</th>
                <td>
                    This class involves the image in each item
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_main_image.CUSTOM {}</th>
                <td>
                    Each image has this class
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_title.CUSTOM {}</th>
                <td>
                    The title of each item
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_excerpt.CUSTOM {}</th>
                <td>
                    The excerpt of each item
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_bar.CUSTOM {}</th>
                <td>
                    This class is the bar where are the icons. Change this if you want to change for example, the background color
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_icons.CUSTOM {}</th>
                <td>
                    This class is the container of the icons (video, image, audio etc)
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.mmphoto.CUSTOM {}</th>
                <td>
                    The class of the icon photo
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.mmvideo.CUSTOM {}</th>
                <td>
                    The class of the icon video
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.mmaudio.CUSTOM {}</th>
                <td>
                    The class of the icon audio
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.mmmore.CUSTOM {}</th>
                <td>
                    The class of the icon more
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_share.CUSTOM {}</th>
                <td>
                    The class of the icon share
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_tooltip.CUSTOM {}</th>
                <td>
                    The class of the tooltip. The tooltip appears when the share icon is clicked
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_paginate_container.CUSTOM {}</th>
                <td>
                    The container of pagination
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_paginate.CUSTOM {}</th>
                <td>
                    This is the class where are the navigation buttons
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_previous.CUSTOM {}</th>
                <td>
                    Button on the top navigation
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_next.CUSTOM {}</th>
                <td>
                    Button on the top navigation
                </td>
            </tr>
            
            <tr valign="top" class="widefat">
                <th scope="row">.hp_extra_info.CUSTOM {}</th>
                <td>
                    Extra information for each item
                </td>
            </tr>
    
        </table>
        
    </div><!-- left -->
    
    
    <div id="left" style="float:left; width:45%;">
    
    	<p>
        	Use "media query" to adjust the properties of CSS according to the resolution of the user.<br />
        </p>
        
        <div style="border:#444 solid 1px; padding:20px;">
        
        	To see how it works, copy the example code below. On the edit page of the slider, check the option Skin > Customising this Skin?. Paste the code into the field that appears, and save. The Title (hp_title class) should change the color and font-size according to the resolution.
          <br /><br />
            <strong>NOTE:</strong> Replace the word CUSTOM by the main class of the Skin selected
		  <br /><br />
			<pre>
            
            /* desktop  */
            
            @media only screen and (min-width: 769px) {
                 .hp_title a.CUSTOM {
                      font-size:18px;
                      color:green;
                 }
            }
            
            /* tablet */
            
            @media only screen and (max-width: 768px) {
                 .hp_title a.CUSTOM {
                      font-size:14px;
                      color:red;
                 }
            }
            
            /* phone */
            
            @media only screen and (max-width: 480px) {
                 .hp_title a.CUSTOM {
                      font-size:12px;
                      color:blue;
                 }
            }
            
            </pre>

        </div>
    
    </div>

</div>