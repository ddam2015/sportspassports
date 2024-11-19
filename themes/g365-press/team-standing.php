<?php
/**
 * Template Name: Team Standings
 */

get_header();
$g365_ad_info = g365_start_ads( $post->ID );

$default_profile_img = get_site_url() . '/wp-content/uploads/event-profiles/g365_profile_placeholder.gif';
// $standings_data = g365_build_standings();
// echo '<pre class="hide">';
// print_r($standings_data);
// echo '</pre>';	

?>

<section id="content" class="grid-x grid-margin-x site-main large-padding-top xlarge-padding-bottom<?php if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_section_class']; ?>" role="main">
	<div class="cell small-12">
		<?php
		if ( $g365_ad_info['go'] ) echo $g365_ad_info['ad_before'] . $g365_ad_info['ad_content'] . $g365_ad_info['ad_after'];
		if ( have_posts() ) : while ( have_posts() ) : the_post();

			get_template_part( 'page-parts/content', get_post_type() );

		endwhile;
		// If no content, include the "No posts found" template.
		else :

			get_template_part( 'page-parts/content', 'none' );

		endif;
    
//                       <table class="wp-block-table alignwide"><thead><tr><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead><tbody><tr><td>Dojo United</td><td>2.33</td><td>7</td><td>3</td><td>3-0</td><td>100.00%</td><td>102</td><td>28</td><td>74</td><td>1</td><td>✓</td></tr><tr><td>SGV 2 Blue</td><td>1.00</td><td>4</td><td>4</td><td>2-2</td><td>50.00%</td><td>84</td><td>67</td><td>17</td><td></td><td>✓</td></tr><tr><td>Team Nikos</td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>21</td><td>112</td><td>-91</td><td><✓/td><td></td></tr></tbody></table>
    
    if ( $g365_stump === "littlehat" ) {
		?>
		<div class="tabs separate grid-x small-up-3 medium-up-4 large-up-7 align-center text-center collapse" id="standing-level-tabs" data-tabs>
      <div class="tabs-title cell is-active">
        <a href="#14U">14U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#13U">13U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#12U">12U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#11U">11U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#10U">10U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#9U">9U</a>
      </div>
      <div class="tabs-title cell">
        <a href="#8U">8U</a>
      </div>
    </div>
    <div id="tables-container" class="tabs-content table-data table-reveal header-padding gset-wrap-tabs standings-tables text-center" data-tabs-content="standing-level-tabs">
      <div class="grid-x tabs-panel small-padding is-active" id="14U">
				<div class="cell gset medium-padding">
					<h2>14U Standings - March 20, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="14U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#14U-open">14U Open</a>
              </div>
							<div class="tabs-title cell">
                <a href="#14U-gold">14U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#14U-silver">14U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#14U-bronze">14U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#14U-copper">14U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="14U-tabs">
              <div class="tabs-panel is-active" id="14U-open">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active"  data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic - Open</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>


 <tr><td>1</td><td>7 Days<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>206</td><td>143</td><td>63</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Fresno Swoosh Elite<span>NorCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>202</td><td>157</td><td>45</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>OGP HQ Black<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>4-1</td><td>80.00%</td><td>164</td><td>142</td><td>22</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Loyalty Vegas Panthers<span>Mountain West</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>185</td><td>162</td><td>23</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>California Bearcats Red<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>173</td><td>209</td><td>-36</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Youball<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>176</td><td>189</td><td>-13</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Gamepoint SB Show<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>159</td><td>145</td><td>14</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>And1 Academy<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>144</td><td>182</td><td>-38</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Team Create Stacey<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>126</td><td>166</td><td>-40</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier I - Open</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>



 
 <tr><td>1</td><td>OGP AZ Black<span>Southwest</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>162</td><td>134</td><td>28</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>AZ Best Select<span>Southwest</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>184</td><td>116</td><td>68</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Blue Chip Nation Gold<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>89</td><td>84</td><td>5</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>B.E. Elite<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>68</td><td>71</td><td>-3</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AZ Sharks<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>78</td><td>95</td><td>-17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team Phoenix<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>48</td><td>129</td><td>-81</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier II - Open</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>Nevada Wolverines</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>250</td><td>143</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>AZ Sharks</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>171</td><td>180</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Hustle Elite </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>153</td><td>122</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP AZ Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>94</td><td>223</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

 
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Washington Spring Qualifier II - Open</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          

                             
 
 <tr><td>1</td><td>West Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>165</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Hustle</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>145</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Young Gunz</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>122</td><td>84</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Kent Elite 2024</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>119</td><td>130</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Washington Warriors</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>75</td><td>76</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team Bradley</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>107</td><td>105</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Style 8 White</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>89</td><td>98</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Total Package</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>70</td><td>122</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="14U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">San Diego Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>


 <tr><td>1</td><td>WCE San Diego<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>219</td><td>151</td><td>68</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Cali Dream<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>212</td><td>249</td><td>-37</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>I-805 Future<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>177</td><td>136</td><td>41</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>GP South Bay Show<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>133</td><td>134</td><td>-1</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AVAC Ballerz<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>106</td><td>123</td><td>-17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Gamepoint IE<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>79</td><td>180</td><td>-101</td><td>&nbsp;</td><td></td></tr>


                          
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          

 
 <tr><td>1</td><td>Lakeshow Elite<span>NorCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>282</td><td>194</td><td>88</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Matt Barnes Elite<span>NorCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>213</td><td>216</td><td>-3</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Oakland Rebels Black<span>NorCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>145</td><td>84</td><td>61</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Yellow Jackets 13u<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>136</td><td>143</td><td>-7</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Santa Cruz Premier Black<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>107</td><td>127</td><td>-20</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Port City<span>NorCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>152</td><td>225</td><td>-73</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Prime Gold<span>NorCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>97</td><td>143</td><td>-46</td><td>&nbsp;</td><td></td></tr>


                          
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

                          

 <tr><td>1</td><td>CVBC Grey<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>240</td><td>171</td><td>69</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP Kings Black<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>261</td><td>201</td><td>60</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Grind City<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>139</td><td>130</td><td>9</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>805 Raptors Red<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>125</td><td>138</td><td>-13</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>G1 Elite Red<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>172</td><td>203</td><td>-31</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>G1 Elite Blue<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>149</td><td>197</td><td>-48</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>WCE Villarruel<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>133</td><td>179</td><td>-46</td><td>&nbsp;</td><td></td></tr>


                          
                          
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                       <tbody>

 <tr><td>1</td><td>Lakeshow Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>104</td><td>112</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Fresno Swoosh Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>193</td><td>178</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Touch Shooting Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>189</td><td>160</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Matt Barnes Elite Black</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>120</td><td>147</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Oakland Rebels Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>140</td><td>136</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Oakland Soldiers EYBL</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>177</td><td>153</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>They Got Next</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>88</td><td>125</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        
                        
                        </tbody>

                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          

 <tr><td>1</td><td>SF Soldiers</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>280</td><td>227</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>210</td><td>224</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Jus B Tuf</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>207</td><td>203</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>East County Hornets</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>229</td><td>233</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Legacy Academy Black </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>182</td><td>221</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                          <tbody>
                            
                            

 <tr><td>1</td><td>Loyalty Vegas Panthers</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>210</td><td>133</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>VEBC 8 Premiere</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>4-2</td><td>66.67%</td><td>281</td><td>243</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Space Jam Elite (Houston, TX)</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>177</td><td>253</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Utah Hard Knox </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>227</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>VEBC 8 Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>218</td><td>214</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>CM Bulls</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>240</td><td>247</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Nevada Wolverines </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>169</td><td>227</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="14U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 
 <tr><td>1</td><td>LA Blue Chip</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>205</td><td>149</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>And1 Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>158</td><td>192</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP HQ Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>175</td><td>214</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Big Red 1</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>202</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>IE Rain</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>213</td><td>228</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP Kings Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>169</td><td>229</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>EBO Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>208</td><td>179</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>OGP HQ Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>209</td><td>172</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Team Create </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>206</td><td>195</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
    
 <tr><td>1</td><td>Jus B Tuf Shane </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>185</td><td>172</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>All Net Elite </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>197</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Lakeshow Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>140</td><td>141</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Flight Elite Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>124</td><td>140</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Team Unmexpected </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>121</td><td>160</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>


                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>CVBC Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>217</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>OGP Kings Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>219</td><td>192</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>805 Raptors</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>139</td><td>179</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Beverly Hills Drem</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>126</td><td>167</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        
                        <tbody>
  
 <tr><td>1</td><td>SF Generals <span>NorCal</span></td><td>1.25</td><td>5</td><td>4</td><td>3-1</td><td>75.00%</td><td>206</td><td>157</td><td>49</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Lakeshow Black<span>NorCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>214</td><td>219</td><td>-5</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>SB Magic<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>191</td><td>207</td><td>-16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Payton's Place<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>134</td><td>122</td><td>12</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>San Jose Dubs<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>156</td><td>158</td><td>-2</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>The House<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>98</td><td>110</td><td>-12</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Santa Cruz Premier<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>134</td><td>157</td><td>-23</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>

                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
                          
   
 <tr><td>1</td><td>G1 Elite White<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>233</td><td>182</td><td>51</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP Kings Blue<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>158</td><td>141</td><td>17</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>VC Swoosh<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>93</td><td>77</td><td>16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SB Vipers Black<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>153</td><td>190</td><td>-37</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>CVBC Orange<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>129</td><td>163</td><td>-34</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
       
 <tr><td>1</td><td>PD Basketball<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>238</td><td>207</td><td>31</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP HQ Blue<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>229</td><td>224</td><td>5</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>OGP Arizona Black<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>228</td><td>192</td><td>36</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SD Bulldogs Gold<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>172</td><td>159</td><td>13</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Gamepoint Elite<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>170</td><td>153</td><td>17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>NM Heat Elite<span>Southwest</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>227</td><td>215</td><td>12</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>OGP Irvine Black<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>196</td><td>199</td><td>-3</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Shooters Elite<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>268</td><td>273</td><td>-5</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>One Family<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>224</td><td>239</td><td>-15</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>10</td><td>Team Klun Brasil<span>International</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>172</td><td>192</td><td>-20</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>11</td><td>JMGP Elite<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>162</td><td>196</td><td>-34</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>12</td><td>PTBA<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>143</td><td>180</td><td>-37</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
  <tr><td>1</td><td>Oakland Rebels Silver</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>131</td><td>127</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>68</td><td>91</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Lakeshow Black</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>68</td><td>63</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Select Brown</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>96</td><td>126</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Oakland Soldiers 13u EYBL</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>217</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>SF Generals</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>181</td><td>158</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Matt Barnes Elite Red</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>104</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>


 <tr><td>1</td><td>San Jose Dubs </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>219</td><td>213</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>215</td><td>190</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>MBE Red</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>215</td><td>264</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Legacy Academy Tohn</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>204</td><td>123</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>CEBA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>184</td><td>197</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>East County Hornets Yellow</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>140</td><td>211</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>SF Soldiers 13U</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>251</td><td>196</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Vallejo Generals </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>190</td><td>183</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Legacy Academy Silver </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>185</td><td>226</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>


                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>



 <tr><td>1</td><td>TKO Basketball</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>278</td><td>159</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Faith Lutheran Crusaders</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>265</td><td>211</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>VEBC 8 White</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>119</td><td>160</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Save Our Sun Ravens</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>3-3</td><td>50.00%</td><td>199</td><td>174</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>VEBC 8 Gray </td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>1-5</td><td>16.67%</td><td>156</td><td>313</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic East</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>


<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>1</td><td>All Out Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>285</td><td>166</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Blue Chip Nation</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>157</td><td>177</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Genesis Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>162</td><td>149</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Hard 2 Guard</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>176</td><td>231</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Big Red 2</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>284</td><td>225</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>PTBA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>174</td><td>180</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>The A-Team</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>137</td><td>180</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>OGP Kings Blue </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>161</td><td>228</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic West</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>



 <tr><td>1</td><td>GSA Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>192</td><td>178</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>SD Dynasty</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>168</td><td>149</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Ocean City</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>204</td><td>223</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Hard 2 Guard 13U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>191</td><td>116</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Team San Diego</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>147</td><td>189</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>A-Game Basketball Black </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>143</td><td>173</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>


 <tr><td>1</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>173</td><td>137</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>198</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Payton's Place </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>176</td><td>203</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Flight Elite Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>73</td><td>145</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Nor Cal Bulls</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>139</td><td>119</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>One Family </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>155</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Flight Elite Silver </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>170</td><td>219</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>


                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

 <tr><td>1</td><td>Team Dojo 13U</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>125</td><td>109</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>CVBC Orange</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>178</td><td>169</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>148</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>CAVS</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>219</td><td>220</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Game Changers</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>143</td><td>166</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="14U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">San Diego Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>     

     
 <tr><td>1</td><td>I-805 Future<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>276</td><td>108</td><td>168</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>KAO<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>154</td><td>128</td><td>26</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>AVAC Ballerz<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>133</td><td>114</td><td>19</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>San Diego Sol<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>169</td><td>199</td><td>-30</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>SD Bulldogs Red<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>89</td><td>151</td><td>-62</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Predators<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>56</td><td>171</td><td>-115</td><td>&nbsp;</td><td></td></tr>

                          
                          
                        </tbody>


                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

 <tr><td>1</td><td>Rage Elite<span>SoCal</span></td><td>1.25</td><td>5</td><td>4</td><td>3-1</td><td>75.00%</td><td>173</td><td>150</td><td>23</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>SB Vipers White<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>170</td><td>170</td><td>0</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>CVBC White<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>121</td><td>130</td><td>-9</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP Kings White<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>117</td><td>131</td><td>-14</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
 <tr><td>1</td><td>OGP Stampede Blue<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>156</td><td>122</td><td>34</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP HQ Orange<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>191</td><td>152</td><td>39</td><td>1</td><td>✓</td></tr>
 <tr><td>3</td><td>Chukla Vista Hawks Caguioa<span>SoCal</span></td><td>0.40</td><td>2</td><td>5</td><td>2-3</td><td>40.00%</td><td>168</td><td>184</td><td>-16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Alley-Oop<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>158</td><td>159</td><td>-1</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Chula Vista Hawks Duran<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>135</td><td>175</td><td>-40</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>PD Basketball<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>150</td><td>166</td><td>-16</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

 <tr><td>1</td><td>NJB Whttier Runnin Rebels</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>188</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Chula Vista Lakers</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>232</td><td>197</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Chula Vista Hawks Caguioa</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>146</td><td>163</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>House of Handlez</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>221</td><td>155</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Chula Vista Hawks Duran</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>141</td><td>185</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP HQ White</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>149</td><td>183</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>OGP Kings Grey </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>154</td><td>190</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>


                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
 
 <tr><td>1</td><td>Rage Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>135</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>G1 Elite Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>215</td><td>184</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Rising Stars</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>126</td><td>144</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP Kings Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>145</td><td>152</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>CVBC White</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>141</td><td>136</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Simi Dream</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>265</td><td>228</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>HD Basketball</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>100</td><td>145</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="14U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="13U">
				<div class="cell gset medium-padding">
					<h2>13U Standings - March 24, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="13U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#13U-open">13U Open</a>
              </div>
							<div class="tabs-title cell">
                <a href="#13U-gold">13U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#13U-silver">13U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#13U-bronze">13U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#13U-copper">13U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="13U-tabs">
              <div class="tabs-panel is-active" id="13U-open">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>Sky Rider<span>Southwest</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>205</td><td>143</td><td>62</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Monstarz<span>Southwest</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>95</td><td>87</td><td>8</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Arizona Select 2025 UAA<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>128</td><td>117</td><td>11</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>AZ Gremlins Black<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>95</td><td>87</td><td>8</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AZ Best Select<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>80</td><td>122</td><td>-42</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Blue Chip Nation<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>92</td><td>163</td><td>-71</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                   
 <tr><td>1</td><td>Jus B Tuf Jared</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>266</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>167</td><td>154</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Team Touch Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>125</td><td>140</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SF Soldiers 12U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>168</td><td>173</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Norcal Bulls</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>230</td><td>204</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>164</td><td>193</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>FEBA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>153</td><td>194</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Legacy Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>135</td><td>190</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                                                   <tbody>
     
 <tr><td>1</td><td>Space Jam Elite (Houston, TX)</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>5-1</td><td>83.33%</td><td>360</td><td>234</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Nevada Wolverines </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>144</td><td>203</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Hunington Beach Hardcore</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>111</td><td>229</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>CM Bulls</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>251</td><td>161</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>S4A</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>296</td><td>205</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>VEBC 6 Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>186</td><td>248</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Faith Lutheran Crusaders</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>2-4</td><td>33.33%</td><td>269</td><td>317</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
          
 <tr><td>1</td><td>Blue Chip Nation</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>214</td><td>204</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Nvada Wolverines</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>134</td><td>171</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>AZ Best Select</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>281</td><td>166</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Hoop Dreams</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>140</td><td>171</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AZ Super Saiyons</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>110</td><td>162</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Washington Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                   <tbody>
          
 <tr><td>1</td><td>Young Gunz</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>178</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>West Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>1</td><td>0-1</td><td>0.00%</td><td>36</td><td>64</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Total Package</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>206</td><td>209</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Style 7 Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>143</td><td>136</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Kent Elite Red</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>84</td><td>103</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>One Team Soliders</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>162</td><td>160</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Yakima Valley Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>112</td><td>97</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Style 7 White </td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>84</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="13U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>Bay Area power<span>NorCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>206</td><td>156</td><td>50</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Payton's Place<span>NorCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>213</td><td>179</td><td>34</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Jus B Tuf-Jared<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>168</td><td>146</td><td>22</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Yellow Jackets 12u<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>143</td><td>137</td><td>6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>The House<span>NorCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>67</td><td>179</td><td>-112</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
 
 <tr><td>1</td><td>GSA Elite<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>186</td><td>159</td><td>27</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>2</td><td>CVBC Grey<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>193</td><td>130</td><td>63</td><td>1</td><td>✓</td></tr>
 <tr><td>3</td><td>Team Dojo<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>81</td><td>118</td><td>-37</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>G1 Elite Red<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>118</td><td>145</td><td>-27</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>SB Magic<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>188</td><td>214</td><td>-26</td><td>&nbsp;</td><td></td></tr>

                               
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>Hard 2 Guard<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>259</td><td>102</td><td>157</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Team San Diego<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>237</td><td>165</td><td>72</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Alley-Oop<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>187</td><td>155</td><td>32</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Touch Black<span>NorCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>134</td><td>215</td><td>-81</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Watts Basketball Academy<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>54</td><td>234</td><td>-180</td><td>&nbsp;</td><td></td></tr>


                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
       
 <tr><td>1</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>78</td><td>85</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>They Got Next</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>85</td><td>93</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Bay Area Power</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>149</td><td>148</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>148</td><td>134</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
       
 <tr><td>1</td><td>Carson D.U.C.K.S</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>276</td><td>201</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>GSA Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>271</td><td>232</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>The Truth 12U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>185</td><td>205</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>The A-Team</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>209</td><td>228</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>The Pack </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>144</td><td>219</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
       
 <tr><td>1</td><td>They Got Next</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>189</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>113</td><td>127</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>SF Soldiers 12u Navy</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>106</td><td>150</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Jus B Tuf Elite </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>222</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Prime Gold </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>175</td><td>164</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Payton's Place </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>151</td><td>207</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="13U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>SF Generals<span>NorCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>210</td><td>166</td><td>44</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Oakland Soliders<span>NorCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>211</td><td>132</td><td>79</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Lakeshow Black<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>124</td><td>149</td><td>-25</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Hoops<span>NorCal</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>55</td><td>99</td><td>-44</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>SB Magic<span>NorCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>88</td><td>142</td><td>-54</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">San Diego Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>San Diego Prospects<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>254</td><td>178</td><td>76</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Soldiers Republic<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>228</td><td>169</td><td>59</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Gamepoint Select<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>158</td><td>108</td><td>50</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SD Bulldogd Gold<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>181</td><td>157</td><td>24</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Sundevils<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>149</td><td>135</td><td>14</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>SD Bulldogs Silver<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>127</td><td>182</td><td>-55</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>SD Bulldogs Red<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>82</td><td>181</td><td>-99</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Army of One<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>58</td><td>127</td><td>-69</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
       
 <tr><td>1</td><td>SF Generals</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>130</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Infinite Training </td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>64</td><td>76</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Team Select Bynum</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>173</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>109</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Lakeshow Black</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>55</td><td>68</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        
                        <tbody>
                        
   
 <tr><td>1</td><td>CA Crush </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>225</td><td>152</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>OGP Irvine Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>208</td><td>194</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>323 Hoops </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>131</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Ocean City</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>140</td><td>162</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Genesis Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>219</td><td>136</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Postive Impact Roadrunners</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>132</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>GSA Elite Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>79</td><td>197</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        
                        </tbody>
                        
                        

                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

                          
 <tr><td>1</td><td>SF Generals</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>191</td><td>136</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Flight Elite Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>112</td><td>107</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Prime White</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>79</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Infinte Training</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>173</td><td>174</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>146</td><td>104</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>They Got Next </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>116</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Flight Elite Silver </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>95</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

 <tr><td>1</td><td>CVBC Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>250</td><td>102</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>323 Hoops</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>182</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings Black </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>95</td><td>120</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Showcase Basketball</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>115</td><td>201</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Team Dojo 12U</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>103</td><td>166</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="13U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
   
 <tr><td>1</td><td>teamEdge<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>224</td><td>150</td><td>74</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Rebels Elite<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>174</td><td>118</td><td>56</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Santa Barbara Basketball Academy<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>209</td><td>147</td><td>62</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>VC Swoosh<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>117</td><td>150</td><td>-33</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>CVBC Orange<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>138</td><td>182</td><td>-44</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP Kings Black<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>119</td><td>136</td><td>-17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>OGP Kings Blue<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>68</td><td>166</td><td>-98</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
     
 <tr><td>1</td><td>San Diego Prospects<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>216</td><td>154</td><td>62</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Gamepoint Elite<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>236</td><td>194</td><td>42</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Dojo Elite White<span>SoCal</span></td><td>0.60</td><td>3</td><td>5</td><td>3-2</td><td>60.00%</td><td>247</td><td>200</td><td>47</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP HQ Black<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>158</td><td>156</td><td>2</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Team Touch Grey<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>134</td><td>132</td><td>2</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Chula Vista Hawks Lazo<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>132</td><td>216</td><td>-84</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>SD Bulldogs Silver<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>110</td><td>181</td><td>-71</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
        
 <tr><td>1</td><td>IE Rain</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>256</td><td>238</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Hoop Code</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>4-2</td><td>66.67%</td><td>273</td><td>237</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>206</td><td>217</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Chula Vista Hawks Lazo</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>164</td><td>244</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Brea Ballers</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>208</td><td>133</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Mavericks Legacy</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>177</td><td>213</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>OGP Kings Blue </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>109</td><td>172</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>


                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
  
 <tr><td>1</td><td>Santa Barbara Basketball Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>195</td><td>139</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Dreamchasers</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>212</td><td>225</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings Blue </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>100</td><td>134</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Beverly Hills Dream </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>136</td><td>186</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Hoop Squad</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>201</td><td>171</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>teamEdge Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>183</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>CVBC Orange</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>118</td><td>155</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="13U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="12U">
				<div class="cell gset medium-padding">
					<h2>12U Standings - March 26, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="12U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#12U-open">12U Open</a>
              </div>
							<div class="tabs-title cell">
                <a href="#12U-gold">12U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#12U-silver">12U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#12U-bronze">12U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#12U-copper">12U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="12U-tabs">
              <div class="tabs-panel is-active" id="12U-open">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
          
 <tr><td>1</td><td>Team SAVS 2026<span>NorCal</span></td><td>1.25</td><td>5</td><td>4</td><td>3-1</td><td>75.00%</td><td>202</td><td>136</td><td>66</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Oakland Soldiers<span>NorCal</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>135</td><td>122</td><td>13</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Lakeshow Elite<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>153</td><td>116</td><td>37</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody>
      
 <tr><td>1</td><td>Monstarz<span>Southwest</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>172</td><td>100</td><td>72</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Sky Rider<span>Southwest</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>174</td><td>145</td><td>29</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>AZ Gremlins Black<span>Southwest</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>205</td><td>138</td><td>67</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP AZ Blue<span>Southwest</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>126</td><td>68</td><td>58</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Arizona Select 2026 UAA<span>Southwest</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>132</td><td>127</td><td>5</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP AZ Black<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>115</td><td>71</td><td>44</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Arcadia Ballers<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>103</td><td>85</td><td>18</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>AZ Best Select<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>75</td><td>72</td><td>3</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Desert Storm Gold<span>Southwest</span></td><td>0.33</td><td>1</td><td>3</td><td>0-3</td><td>0.00%</td><td>61</td><td>167</td><td>-106</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>10</td><td>Power Elite<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>52</td><td>80</td><td>-28</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>11</td><td>Desert Strom Black<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>45</td><td>98</td><td>-53</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>12</td><td>AZ Super Saiyans<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>61</td><td>143</td><td>-82</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
     
 <tr><td>1</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>125</td><td>112</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>S4A</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>82</td><td>75</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>San Jose Spartans</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>66</td><td>90</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Lakeshow Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>135</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>94</td><td>66</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Central Valley Storm </td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>59</td><td>103</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>

 <tr><td>1</td><td>CM Bulls </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>274</td><td>175</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>VEBC 6 Gray</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>159</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Utah Hard Knox</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>269</td><td>207</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>VEBC 6 White</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>189</td><td>257</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>S4A</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>248</td><td>161</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Save Our Suns Ravens </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>207</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>VEEBC 6 Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>148</td><td>199</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>VEBC Black </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>75</td><td>262</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody>
                                                 
     
 <tr><td>1</td><td>AZ Best Select</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>114</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Monstarz</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>212</td><td>178</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP AZ Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>146</td><td>153</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Arcadia Ballers</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>140</td><td>124</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AZ Super Saiyons</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>117</td><td>75</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Hustle Elite </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>81</td><td>106</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Washington Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                                       <tbody>
    
 <tr><td>1</td><td>Game Time Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>126</td><td>91</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Style 6 Black</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>80</td><td>90</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Kent Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>68</td><td>102</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Washington Warriors</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>128</td><td>91</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Young Gunz</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>66</td><td>78</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team Bradley</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>74</td><td>90</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="12U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">San Diego Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>Lock Up Select<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>265</td><td>152</td><td>113</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>San Diego Prospects<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>189</td><td>165</td><td>24</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Gamepoint IE<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>158</td><td>200</td><td>-42</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>I-805 Future<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>118</td><td>146</td><td>-28</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AVAC Hawk Hoops American<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>163</td><td>161</td><td>2</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Prodigy<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>90</td><td>159</td><td>-69</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

 <tr><td>1</td><td>SB Magic Red<span>SoCal</span></td><td>1.25</td><td>5</td><td>4</td><td>3-1</td><td>75.00%</td><td>210</td><td>185</td><td>25</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>GSA Elite<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>162</td><td>164</td><td>-2</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Team Dojo<span>SoCal</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>168</td><td>103</td><td>65</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>G1 Elite Red<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>125</td><td>144</td><td>-19</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Showcase Basketball<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>102</td><td>167</td><td>-65</td><td>&nbsp;</td><td></td></tr>


                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
 
 <tr><td>1</td><td>YBA Elite<span>NorCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>223</td><td>117</td><td>106</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>San Diego Prospects<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>249</td><td>180</td><td>69</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>OGP Arizona Black<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>4-1</td><td>80.00%</td><td>209</td><td>168</td><td>41</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Squad<span>SoCal</span></td><td>0.40</td><td>2</td><td>5</td><td>2-3</td><td>40.00%</td><td>178</td><td>236</td><td>-58</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>OGP HQ Black<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>162</td><td>215</td><td>-53</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team Nikos Black<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>129</td><td>234</td><td>-105</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
               
 <tr><td>1</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>204</td><td>162</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Team SAVS 2026</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>252</td><td>195</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>FEBA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>167</td><td>161</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>DKDC</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>158</td><td>222</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Reign City Basketball</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>152</td><td>193</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>


                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>


                                                   <tbody>
                                                     
           
 <tr><td>1</td><td>Lakeshow Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>102</td><td>88</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>One Family</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>123</td><td>122</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Team Jr Arsenal</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>64</td><td>116</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Wright Legacy </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>162</td><td>107</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>122</td><td>102</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>BullDawgs Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>135</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Bay Area Eagles</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>146</td><td>184</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>

                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="12U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
    
 <tr><td>1</td><td>One Family White<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>239</td><td>157</td><td>82</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP Arizona Blue<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>200</td><td>195</td><td>5</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Prodigy Basketball<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>164</td><td>120</td><td>44</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Bakersfield Elite Ballers<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>118</td><td>129</td><td>-11</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>San Diego Sol Heat<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>131</td><td>148</td><td>-17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team San Diego<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>165</td><td>193</td><td>-28</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>OGP HQ White<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>132</td><td>166</td><td>-34</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Team Touch Black<span>NorCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>143</td><td>184</td><td>-41</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
  
 <tr><td>1</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>74</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Norcal Bulls </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>146</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Team Touch Black </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>177</td><td>134</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SV Swish Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>164</td><td>132</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Clutch Elite </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>156</td><td>217</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic East</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                  
 <tr><td>1</td><td>OGP HQ Black </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>184</td><td>123</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Bakersfield Elite Ballers </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>187</td><td>207</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>The Truth 11U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>126</td><td>150</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>EBO</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>179</td><td>126</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Positive Impact Roadrunners </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>159</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team FAB</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>133</td><td>206</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic West</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
         
 <tr><td>1</td><td>Hoop Code</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>234</td><td>167</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>The A-Team</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>221</td><td>182</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Pro Skills </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>171</td><td>124</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SDOT Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>118</td><td>149</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>GSA Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>82</td><td>204</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        </tbody>


                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
                          
 <tr><td>1</td><td>Prime Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>118</td><td>81</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Palo Alto Flight</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>214</td><td>142</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Nonstop Grind</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>224</td><td>192</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Flight Elite Silver</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>70</td><td>178</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Lakeshow Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>231</td><td>175</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Nor Cal Mighty Ducks </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>76</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Nemesis </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>88</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Flight Elite Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>101</td><td>103</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
            
 <tr><td>1</td><td>G1 Elite 11U Red</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>273</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Beverly Hills Dream</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>169</td><td>167</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>CVBC Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>181</td><td>228</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Rising Stars Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>173</td><td>215</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Rage Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>115</td><td>104</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>CAVS</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>112</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Rise Up LA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>57</td><td>173</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="12U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
              
 <tr><td>1</td><td>KAO<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>158</td><td>88</td><td>70</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Soldiers Republic<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>157</td><td>164</td><td>-7</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Army of One<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>92</td><td>96</td><td>-4</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>AVAC Ballerz<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>74</td><td>133</td><td>-59</td><td>&nbsp;</td><td></td></tr>


                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SLA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
             
 <tr><td>1</td><td>Rage Elite<span>NorCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>174</td><td>104</td><td>70</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>CVBC Grey<span>NorCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>214</td><td>146</td><td>68</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Santa Barbara Basketball Academy <span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>151</td><td>144</td><td>7</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SB Magic Black<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>119</td><td>130</td><td>-11</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>OGP Kings<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>98</td><td>115</td><td>-17</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>VC Swoosh<span>NorCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>66</td><td>102</td><td>-36</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Rebels Elite<span>NorCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>72</td><td>153</td><td>-81</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
     
 <tr><td>1</td><td>Chula Vista Hawks Ilagan<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>194</td><td>118</td><td>76</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>PD Basketball<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>174</td><td>161</td><td>13</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Chula Vista Hawks Pitel<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>150</td><td>127</td><td>23</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP HQ Blue<span>SoCal</span></td><td>0.40</td><td>2</td><td>5</td><td>2-3</td><td>40.00%</td><td>149</td><td>117</td><td>32</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Youball <span>SoCal</span></td><td>0.20</td><td>1</td><td>5</td><td>1-4</td><td>20.00%</td><td>116</td><td>182</td><td>-66</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>We R 1<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>80</td><td>158</td><td>-78</td><td>&nbsp;</td><td></td></tr>
                       
                        
                        </tbody>
                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic East</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
                      
 <tr><td>1</td><td>Chula Vista Hawks Pitel</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>114</td><td>93</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>SD Dynasty</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>152</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP HQ Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>135</td><td>163</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP HQ White </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>187</td><td>112</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>OGP Kings</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>183</td><td>205</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>ALR Athletics </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>136</td><td>178</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic West</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
          
 <tr><td>1</td><td>Team San Diego</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>206</td><td>162</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Brea Ballers</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>209</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Chula Vista Hawks Ilagan </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>136</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Carson D.U.C.K.S</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>113</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>House of Handlez</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>104</td><td>152</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                          
  
 <tr><td>1</td><td>Showcase Basketball</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>175</td><td>171</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Rising Stars Platinum </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>141</td><td>148</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>97</td><td>102</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Santa Barbara Basketball Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>207</td><td>147</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>G1 Elite White</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>166</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>CVBC Orange</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>66</td><td>128</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="12U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="11U">
				<div class="cell gset medium-padding">
					<h2>11U Standings - March 26, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="11U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#11U-gold">11U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#11U-silver">11U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#11U-bronze">11U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#11U-copper">11U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="11U-tabs">
              <div class="tabs-panel is-active" id="11U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 

                           
 <tr><td>1</td><td>Sky Rider<span>Southwest</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>144</td><td>102</td><td>42</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Landsharks<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>194</td><td>128</td><td>66</td><td>1</td><td>✓</td></tr>
 <tr><td>3</td><td>One Family Blue<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>187</td><td>123</td><td>64</td><td>1</td><td>✓</td></tr>
 <tr><td>4</td><td>Payton's Place<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>236</td><td>181</td><td>55</td><td>1</td><td>✓</td></tr>
 <tr><td>5</td><td>Oakland Soldiers<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>225</td><td>160</td><td>65</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>6</td><td>G1 Elite Blue<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>212</td><td>177</td><td>35</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>7</td><td>AZ Phenoms<span>Southwest</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>156</td><td>105</td><td>51</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>8</td><td>OGP Arizona Blue<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>190</td><td>152</td><td>38</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>9</td><td>Sneakersteal Hoops<span>NorCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>129</td><td>119</td><td>10</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>10</td><td>AZ Super Saiyans<span>Southwest</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>136</td><td>110</td><td>26</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>11</td><td>Hoop Code<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>82</td><td>60</td><td>22</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>12</td><td>Prime Gold<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>114</td><td>95</td><td>19</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>13</td><td>Monstarz<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>80</td><td>74</td><td>6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>14</td><td>SB Vipers Black<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>148</td><td>154</td><td>-6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>15</td><td>Young Kings<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>116</td><td>125</td><td>-9</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>16</td><td>SB Magic<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>88</td><td>87</td><td>1</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>17</td><td>Lock Up<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>102</td><td>108</td><td>-6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>18</td><td>OGP Kings Black<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>107</td><td>119</td><td>-12</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>19</td><td>AZ Best Select<span>Southwest</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>120</td><td>158</td><td>-38</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>20</td><td>Major Game Hoops Red<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>73</td><td>113</td><td>-40</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>21</td><td>OGP HQ Black<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>99</td><td>129</td><td>-30</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>22</td><td>Landsharks<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>107</td><td>137</td><td>-30</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>23</td><td>JMGP Elite<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>102</td><td>144</td><td>-42</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>24</td><td>805 Raptors Red<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>76</td><td>92</td><td>-16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>25</td><td>Showcase Basketball<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>120</td><td>138</td><td>-18</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>26</td><td>Arizona Select 2027 Black<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>67</td><td>96</td><td>-29</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>27</td><td>Hoops City Grind<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>58</td><td>115</td><td>-57</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>28</td><td>Lakeshow Elite<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>85</td><td>162</td><td>-77</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>29</td><td>Desert Storm<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>36</td><td>116</td><td>-80</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
                  
                           
 <tr><td>1</td><td>Landsharks<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>194</td><td>128</td><td>66</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>One Family Blue<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>187</td><td>123</td><td>64</td><td>1</td><td>✓</td></tr>
 <tr><td>3</td><td>Payton's Place<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>236</td><td>181</td><td>55</td><td>1</td><td>✓</td></tr>
 <tr><td>4</td><td>Oakland Soldiers<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>225</td><td>160</td><td>65</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>5</td><td>G1 Elite Blue<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>212</td><td>177</td><td>35</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>6</td><td>SB Vipers Black<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>148</td><td>154</td><td>-6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Young Kings<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>116</td><td>125</td><td>-9</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>SB Magic<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>88</td><td>87</td><td>1</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Lock Up<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>102</td><td>108</td><td>-6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>10</td><td>OGP Kings Black<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>107</td><td>119</td><td>-12</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>11</td><td>Major Game Hoops Red<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>73</td><td>113</td><td>-40</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>12</td><td>OGP HQ Black<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>99</td><td>129</td><td>-30</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>13</td><td>Landsharks<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>107</td><td>137</td><td>-30</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>14</td><td>JMGP Elite<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>102</td><td>144</td><td>-42</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>15</td><td>805 Raptors Red<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>76</td><td>92</td><td>-16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>16</td><td>Showcase Basketball<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>120</td><td>138</td><td>-18</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>17</td><td>Hoops City Grind<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>58</td><td>115</td><td>-57</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>18</td><td>Lakeshow Elite<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>85</td><td>162</td><td>-77</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
                         
 <tr><td>1</td><td>Sneakersteal Hoops<span>NorCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>129</td><td>119</td><td>10</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Prime Gold<span>NorCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>114</td><td>95</td><td>19</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

                                 
 <tr><td>1</td><td>Sky Rider<span>Southwest</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>144</td><td>102</td><td>42</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>AZ Phenoms<span>Southwest</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>156</td><td>105</td><td>51</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>OGP Arizona Blue<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>190</td><td>152</td><td>38</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>4</td><td>AZ Super Saiyans<span>Southwest</span></td><td>0.67</td><td>2</td><td>3</td><td>2-1</td><td>66.67%</td><td>136</td><td>110</td><td>26</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Hoop Code<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>82</td><td>60</td><td>22</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Monstarz<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>80</td><td>74</td><td>6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>AZ Best Select<span>Southwest</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>120</td><td>158</td><td>-38</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Arizona Select 2027 Black<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>67</td><td>96</td><td>-29</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Desert Storm<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>36</td><td>116</td><td>-80</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li><li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Washington Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                </ul>
							</div>
              <div class="tabs-panel" id="11U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">San Diego Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>SD Bulldogs Red 1<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>148</td><td>66</td><td>82</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>KAO<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>104</td><td>117</td><td>-13</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>AVAC Ballerz 10U<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>96</td><td>103</td><td>-7</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Predators<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>63</td><td>125</td><td>-62</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 

 <tr><td>1</td><td>Hoop City Grind</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>1-1</td><td>50.00%</td><td>62</td><td>62</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>50</td><td>76</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Lakeshow Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>165</td><td>76</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Select Hall</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>88</td><td>151</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>




                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                                       <tbody>
    
 <tr><td>1</td><td>Landsharks</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>180</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>OGP HQ Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>6</td><td>3-3</td><td>50.00%</td><td>171</td><td>124</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Major Game Hoops Red</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>3-2</td><td>60.00%</td><td>174</td><td>156</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Ocean City</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>129</td><td>193</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Baskersfield Elite Ballers 10U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>192</td><td>105</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP Kings Black</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>2-3</td><td>40.00%</td><td>143</td><td>213</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Pro Skills </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>125</td><td>208</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>

   
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                                                                          <tbody>

 <tr><td>1</td><td>Infinite Training </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>134</td><td>90</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Flight Elite Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>68</td><td>118</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>One Family White </td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>75</td><td>100</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Lakeshow Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>140</td><td>73</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Payton's Place</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>200</td><td>157</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Fresno Wildcats</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>2-1</td><td>66.67%</td><td>85</td><td>107</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Bay Area Eagles</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>125</td><td>146</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        
                        </tbody>

                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 <tr><td>1</td><td>Major Game Hoops </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>185</td><td>140</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>OGP Kings Black</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>153</td><td>110</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>805 Raptors</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>125</td><td>111</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Rie Up LA</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>79</td><td>138</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="11U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 
 <tr><td>1</td><td>G1 Elite White<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>149</td><td>118</td><td>31</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>SB Magic<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>187</td><td>106</td><td>81</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>teamEdge<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>157</td><td>116</td><td>41</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>CVBC<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>148</td><td>117</td><td>31</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>VC Swoosh<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>68</td><td>117</td><td>-49</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP Kings Blue<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>42</td><td>177</td><td>-135</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>OGP HQ Blue<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>205</td><td>113</td><td>92</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP Arizona Blue<span>Southwest</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>164</td><td>111</td><td>53</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Shooters Elite<span>SoCal</span></td><td>0.60</td><td>3</td><td>5</td><td>3-2</td><td>60.00%</td><td>156</td><td>218</td><td>-62</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>We R 1 Black<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>141</td><td>141</td><td>0</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>San Diego Prospects<span>SoCal</span></td><td>0.20</td><td>1</td><td>5</td><td>1-4</td><td>20.00%</td><td>181</td><td>197</td><td>-16</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Dojo Elite Grey<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>91</td><td>158</td><td>-67</td><td>&nbsp;</td><td></td></tr>
     



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody>
                        

 <tr><td>1</td><td>PTBA</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>144</td><td>127</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Campus 5th</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>167</td><td>122</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>OGP Kings Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>106</td><td>138</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Positive Impact Roadrunners</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>150</td><td>86</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>GSA Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>104</td><td>106</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>OGP Irvine Black </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>64</td><td>135</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        
<tbody>
                        
    
 <tr><td>1</td><td>Team Dojo</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>172</td><td>107</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>teamEdge Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>156</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>CVBC Grey</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>103</td><td>121</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Hoop Squad</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>158</td><td>147</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>OGP Kings Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>46</td><td>145</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
    
  
                        </tbody>

                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="11U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>Alley-Oop<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>210</td><td>133</td><td>77</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>PTBA<span>SoCal</span></td><td>1.00</td><td>5</td><td>5</td><td>4-1</td><td>80.00%</td><td>190</td><td>121</td><td>69</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Chula Vista Hawks Duran<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>124</td><td>145</td><td>-21</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>We R 1 White<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>86</td><td>149</td><td>-63</td><td>&nbsp;</td><td></td></tr>
            


                        </tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="10U">
				<div class="cell gset medium-padding">
					<h2>10U Standings - April 7, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="10U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#10U-gold">10U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#10U-silver">10U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#10U-bronze">10U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#10U-copper">10U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="10U-tabs">
              <div class="tabs-panel is-active" id="10U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
   


 <tr><td>1</td><td>Oakland Soldiers<span>NorCal</span></td><td>1.67</td><td>5</td><td>3</td><td>3-0</td><td>100.00%</td><td>112</td><td>56</td><td>56</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Payton's Place<span>NorCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>133</td><td>116</td><td>17</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Bay Area Eagles<span>NorCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>95</td><td>98</td><td>-3</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Hoops<span>NorCal</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>21</td><td>91</td><td>-70</td><td>&nbsp;</td><td></td></tr>

                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>G1 Elite<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>173</td><td>83</td><td>90</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>CVBC Grey<span>SoCal</span></td><td>1.00</td><td>4</td><td>4</td><td>3-1</td><td>75.00%</td><td>134</td><td>75</td><td>59</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>NWTA Celtics<span>SoCal</span></td><td>0.33</td><td>1</td><td>3</td><td>1-2</td><td>33.33%</td><td>55</td><td>94</td><td>-39</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SB Magic<span>SoCal</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>28</td><td>59</td><td>-31</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>SB Vipers Black<span>SoCal</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>19</td><td>98</td><td>-79</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
  
 <tr><td>1</td><td>Baskersfield Elite Ballers<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>204</td><td>105</td><td>99</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>OGP Arizona Black<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>153</td><td>163</td><td>-10</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>ALR Gravity<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>107</td><td>94</td><td>13</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>AC Green Elite<span>Southwest</span></td><td>0.40</td><td>2</td><td>5</td><td>2-3</td><td>40.00%</td><td>145</td><td>195</td><td>-50</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>One Family Blue<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>126</td><td>134</td><td>-8</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Team Nikos Black<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>119</td><td>163</td><td>-44</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        

   
 <tr><td>1</td><td>OGP AZ Black<span>Southwest</span></td><td>1.33</td><td>4</td><td>3</td><td>2-1</td><td>66.67%</td><td>108</td><td>103</td><td>5</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>AZ Phenoms<span>Southwest</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>109</td><td>100</td><td>9</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>AC Green Elite<span>Southwest</span></td><td>0.50</td><td>1</td><td>2</td><td>1-1</td><td>50.00%</td><td>73</td><td>67</td><td>6</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team F.O.E<span>Southwest</span></td><td>0.00</td><td>0</td><td>2</td><td>0-2</td><td>0.00%</td><td>59</td><td>79</td><td>-20</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier III</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
                        
     
 <tr><td>1</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>172</td><td>75</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Triple Threat</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>114</td><td>116</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Jus B Tuf Ahmed</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>126</td><td>105</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Legacy Academy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>55</td><td>171</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        </tbody>

                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody>
        
 <tr><td>1</td><td>Hoop Code</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>150</td><td>81</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Mavericks Legacy</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>112</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Pro Skills</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>131</td><td>115</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>SD Jaguars</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>58</td><td>147</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Arizona Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
                        
        

 <tr><td>1</td><td>AC Green Elite</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>194</td><td>87</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>AZ Gym Rats</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>178</td><td>95</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Avondale Hawks</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>51</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>AZ Battle</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>76</td><td>122</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>AZ Swish</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>37</td><td>131</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>                        
                        
                        </tbody>

                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Washington Spring Qualifier II</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
                        
<tr><td>1</td><td>Game Time Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>3-0</td><td>100.00%</td><td>112</td><td>75</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>NW Prodigy</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>87</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Style 4 Black</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>0-2</td><td>0.00%</td><td>44</td><td>67</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>                        
                        
                        
                        </tbody>

                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>

                        <tbody>
                        

 <tr><td>1</td><td>Bay Area Blitz</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>205</td><td>125</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Flight Elite Black</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>87</td><td>129</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Nor Cal Mighty Ducks</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>182</td><td>160</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Palo Alto Flight</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>5-0</td><td>100.00%</td><td>214</td><td>148</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Oakland Rebels</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>75</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>Prime Gold</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>87</td><td>89</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Team Jr Arsenal </td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>3-1</td><td>75.00%</td><td>126</td><td>103</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Payton's Place</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>62</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>Team Pull Up</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>87</td><td>132</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>10</td><td>Boise Slam Green</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>184</td><td>109</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>11</td><td>Bay Area Eagles</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>64</td><td>88</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>12</td><td>One Family Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>1-2</td><td>33.33%</td><td>100</td><td>102</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>13</td><td>Flight Elite Blue</td><td>&nbsp;</td><td>&nbsp;</td><td>3</td><td>0-3</td><td>0.00%</td><td>29</td><td>123</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>                        
                        
                        
                        </tbody>

                        
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="10U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        
                                               <tbody><tr><td>Standings in process.</td></tr></tbody>

                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="10U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>One Family White<span>SoCal</span></td><td>1.20</td><td>6</td><td>5</td><td>4-1</td><td>80.00%</td><td>192</td><td>132</td><td>60</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Team Create Averill<span>SoCal</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>167</td><td>135</td><td>32</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>OGP HQ Black<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>142</td><td>101</td><td>41</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Nikos White<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>3-1</td><td>75.00%</td><td>133</td><td>96</td><td>37</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Shooters Elite<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>111</td><td>119</td><td>-8</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>6</td><td>SD Bulldogs Red<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>121</td><td>108</td><td>13</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>7</td><td>Watts Basketball Academy<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>72</td><td>125</td><td>-53</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>8</td><td>Chula Vista Hawks Duran <span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>72</td><td>130</td><td>-58</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>9</td><td>FRO<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>66</td><td>130</td><td>-64</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="10U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="9U">
				<div class="cell gset medium-padding">
					<h2>9U Standings - April 7, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="9U-tabs" data-tabs>
							<div class="tabs-title cell">
                <a href="#9U-gold">9U Gold</a>
              </div>
							<div class="tabs-title cell">
                <a href="#9U-silver">9U Silver</a>
              </div>
							<div class="tabs-title cell">
                <a href="#9U-bronze">9U Bronze</a>
              </div>
							<div class="tabs-title cell">
                <a href="#9U-copper">9U Copper</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="9U-tabs">
              <div class="tabs-panel" id="9U-gold">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
         
 <tr><td>1</td><td>Squad<span>SoCal</span></td><td>1.40</td><td>7</td><td>5</td><td>5-0</td><td>100.00%</td><td>197</td><td>89</td><td>108</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Avondale Hawks<span>Southwest</span></td><td>0.80</td><td>4</td><td>5</td><td>3-2</td><td>60.00%</td><td>173</td><td>130</td><td>43</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>House Of Handelz<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>165</td><td>96</td><td>69</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>AVAC Hawk Hopops Gray<span>SoCal</span></td><td>0.25</td><td>1</td><td>4</td><td>1-3</td><td>25.00%</td><td>49</td><td>143</td><td>-94</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Alley-Oop<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>60</td><td>186</td><td>-126</td><td>&nbsp;</td><td></td></tr>
     



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Las Vegas Spring Qualifier I</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
  
 <tr><td>1</td><td>Team Vegas 8u</td><td>&nbsp;</td><td>&nbsp;</td><td>2</td><td>2-0</td><td>100.00%</td><td>40</td><td>15</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>CM Bulls</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>4-0</td><td>100.00%</td><td>124</td><td>52</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>VEBC 3 White</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>79</td><td>133</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Vegas </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>1-4</td><td>20.00%</td><td>54</td><td>97</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>



                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">President's Day Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>AVAC Hawk Hoops National </td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>194</td><td>165</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>Hoop Code</td><td>&nbsp;</td><td>&nbsp;</td><td>5</td><td>4-1</td><td>80.00%</td><td>215</td><td>111</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Pro Skills</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>2-2</td><td>50.00%</td><td>105</td><td>153</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>OGP HQ 9U</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>1-3</td><td>25.00%</td><td>84</td><td>132</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>ALR Athletics</td><td>&nbsp;</td><td>&nbsp;</td><td>4</td><td>0-4</td><td>0.00%</td><td>84</td><td>121</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                       
                        
                        
                        
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Bay Area Spring Qualifier IV</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        
                        <tbody>
                        
            
 <tr><td>1</td><td>Flight Elite Black</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>4-0</td><td>&nbsp;</td><td>146</td><td>66</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>2</td><td>One Family 10U White</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>1-2</td><td>&nbsp;</td><td>76</td><td>69</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>3</td><td>Nor Cal Mighty Ducks </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>2-2</td><td>&nbsp;</td><td>85</td><td>121</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>Team Jr. Arsenal </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>2-2</td><td>&nbsp;</td><td>98</td><td>108</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>5</td><td>Oakland Rebels Black </td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>0-3</td><td>&nbsp;</td><td>60</td><td>101</td><td>&nbsp;</td><td>&nbsp;</td><td></td></tr>
                        
                        
                        
                        </tbody>

                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="9U-silver">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Silver</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="9U-bronze">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                               <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                                                <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>


                                                 <tbody><tr><td>Standings in process.</td></tr></tbody>

                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Bronze</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
              <div class="tabs-panel" id="9U-copper">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">West Coast - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">SoCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest - Copper</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="grid-x tabs-panel small-padding" id="8U">
				<div class="cell gset medium-padding">
					<h2>8U Standings - April 7, 2020</h2>
					<div>
						<nav class="tabs separate grid-x small-up-3 medium-up-5 align-center text-center collapse medium-padding-bottom small-padding-top" id="8U-tabs" data-tabs>
							<div class="tabs-title cell is-active">
                <a href="#8U-open">8U</a>
              </div>
            </nav>
						<div class="standings-data tabs-content table-data" data-tabs-content="8U-tabs">
              <div class="tabs-panel is-active" id="8U-open">
                <ul class="accordion"  data-accordion data-allow-all-closed="true">
                  <li class="accordion-item is-active" data-accordion-item>
                    <a href="#" class="accordion-title">LA Spring Qualifier</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
           
 <tr><td>1</td><td>G1 Elite Red<span>SoCal</span></td><td>1.25</td><td>5</td><td>4</td><td>3-1</td><td>75.00%</td><td>122</td><td>89</td><td>33</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Rebels Elite<span>SoCal</span></td><td>1.00</td><td>3</td><td>3</td><td>2-1</td><td>66.67%</td><td>88</td><td>90</td><td>-2</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Young Kings<span>SoCal</span></td><td>0.00</td><td>0</td><td>3</td><td>0-3</td><td>0.00%</td><td>51</td><td>82</td><td>-31</td><td>&nbsp;</td><td></td></tr>
       

                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">MLK Classic</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody>
                        
 
 <tr><td>1</td><td>Squad<span>SoCal</span></td><td>1.50</td><td>6</td><td>4</td><td>4-0</td><td>100.00%</td><td>158</td><td>48</td><td>110</td><td>1</td><td>✓</td></tr>
 <tr><td>2</td><td>Dojo Elite Blue<span>SoCal</span></td><td>0.75</td><td>3</td><td>4</td><td>2-2</td><td>50.00%</td><td>86</td><td>101</td><td>-15</td><td>&nbsp;</td><td>✓</td></tr>
 <tr><td>3</td><td>Team Nikos<span>SoCal</span></td><td>0.50</td><td>2</td><td>4</td><td>2-2</td><td>50.00%</td><td>59</td><td>94</td><td>-35</td><td>&nbsp;</td><td>&nbsp;</td></tr>
 <tr><td>4</td><td>FRO<span>SoCal</span></td><td>0.00</td><td>0</td><td>4</td><td>0-4</td><td>0.00%</td><td>43</td><td>103</td><td>-60</td><td>&nbsp;</td><td></td></tr>
              

                          
                        </tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">NorCal</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Southwest</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Mountain West</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                       
                                               <tbody><tr><td>Standings in process.</td></tr></tbody>
                        
                      </table>
                    </div>
                  </li>
                  <li class="accordion-item" data-accordion-item>
                    <a href="#" class="accordion-title">Pacific Northwest</a>
                    <div class="accordion-content" data-tab-content>
                      <table class="wp-block-table alignwide"><thead><tr><th></th><th>Team</th><th>Average Points</th><th>Total Points</th><th>Games</th><th class="text-nowrap-medium">W-L</th><th>Win %</th><th>PF</th><th>PA</th><th>PD</th><th><span class="table-champ-col" title="Championships">Championships</span></th><th>Cup Invite</th></tr></thead>
                        <tbody><tr><td>Standings in process.</td></tr></tbody>
                      </table>
                    </div>
                  </li>
                </ul>
							</div>
						</div>
					</div>
				</div>
			</div>
      <!-- end levels -->
		</div>
    <?php } ?>
	</div>
</section>

<?php get_footer(); ?>