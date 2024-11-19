<?php
  /**
 * Template Name: OGP Roster Data
 */
get_header();
?>
<section id="content" class="site-main width-hd xlarge-padding-top">
<?php 
  $boys_divisions = ["Kindergarten"=>[
    "Bucks"=>["Brad Carter", "Mon, 5pm, Court 5", ["Bennett Lohman", "Braxton Gardner", "James McAndrew", "Noah Lilly", "Lincoln Paletz", "Nico Sorensen", "Nolan Carvalho", "Daxton Carter", "Jack Tenerelli", "Jimmy  Haldorsen"]],
    "Clippers"=>["Dane DeCasas", "Mon 4pm Court 8", ["Drew Williams", "Wesley DeCasas", "Jordan Moore", "Jarrett Peetz", "John Carl Swoboda", "Luke Miazga", "Kai Fleishman", "Maxwell Schultz", "Gavin Mesri", "James  MacLeod"]],
    "Lakers"=>["Justin Goldstein", "Mon 5pm Court 5", ["Tanner Lynch", "William Taylor", "Drew Biglin", "Jacob Borrmann", "Vincent Artukovic", "Brendan King", "James Nishimura", "Journey Davis", "Luca Goldstein", "Derek Navarro"]],
    "Rockets"=>["Sean Sheller", "Wed 4pm Court 5", ["Jackson Eberhardt", "Tanner Lynch", "Hudson Waken", "Owen Ruziecki", "Jack  Bousky", "Cal Hudson", "Wyatt Sheller", "Braden Burnett", "Nicky Segal", "Gavin White"]],
    "Hornets"=>["Kyle Vaughn", "Mon 4pm Court 8", ["Curren Steinbruner", "Elias Turnbull", "Jacob Fleskoski", "Cohen Vaughn", "Graham Rodenbour", "Luke Meister", "Ronnie Bullocks", "Benjamin Eltringham", "Nathan Leshchuk", "Hudson McGee"]],
    "Timberwolves"=>["Matt Walter", "Tues 4pm Court 5", ["Easton de Montesquiou", "Cruz Rodriguez", "Keegan Baker", "Harvey Walter", "Wyatt Sandifer", "Walter Young", "Luke Fowler", "Alice McKenney", "Adeline Monty", "Rowen Rodriguez"]],
    "Celtics"=>["Steve Tracy", "Wed 4pm Court 5", ["Ronald Branning III", "Trevor Qualls", "Wyatt Nausha", "Preston Chandler", "Lincoln Freeman", "Brecken Tracy", "Harrison Hallstead", "Everett Lipton", "Jaden Cuthbert", "Parker Andersen"]],
    "Jazz"=>["Mark Read", "Tues 4pm Court 5", ["Axel Townsend", "Bradley Bloom", "Camden Hahn", "Gavin Cinquemani", "Ford Gahagan", "Hudson Read", "Aston Matsuishi", "JJ DeGroot", "Morgan Chaimowitz", "Julia Mock"]]
  ],
"1st Grade"=>[
  "Rockets"=>["Jonathan Chin", "N/A", ["Jase Chin", "Kaeton Zhang", "Ayaan Talatam", "Miles Lee", "Max Wong", "Ozan Ekener", "Evelyn Ly", "Russell De La Cruz", "Wesley Shier", "Lucas Wang"]],
  "Bucks"=>["Jamin Jannard", "Tues 4pm Court 8", ["Bear Jannard", "Lincoln Newnam", "Owen Eggers", "Jon Keeney", "Jacob Hayden", "Charles Khan", "Kai Packman", "Jack Snyder", "Matthew Weber", "Noah Silverthorne"]],
  "Clippers"=>["Mike Radford", "Tues 4pm Court 8", ["Tavin Radford", "Nixon Lynch", "Lucas Kelly", "Tanner Smith", "Richard Parker", "Ryan Richardson", "Mason Stonich", "Alan Lavi", "Finn Koopman", "Ashton Negrete"]],
  "Lakers"=>["Kurt Cymerint", "Thurs 4pm Court 5", ["Jace Davies", "Bowie Davis", "Kian Patel", "Lucca Santwire", "Logan Kabine", "Wyatt Patterson", "Noah Cymerint", "Mac Fitzgerald", "Sam Tanner"]],
  "Hornets"=>["Navin Jain", "Mon 5pm Court 8", ["Greyson Cole", "Carson Fowler", "Benjamin Somers", "Arman Wakefield", "Cody Gray", "Gibson Pirayoff", "Jake Weingarten", "Mateo Camacho", "Crew Kennedy"]],
  "Timberwolves"=>["Trace Haggard", "Thurs 4pm Court 5", ["Mateo Haggard", "Pierson Light", "Jayden Shane de Leon", "Gavin Cole", "Ace Townsend", "Noah Montoya", "Carson Hearn", "Jack Webster", "Asher Klein"]],
  "Jazz"=>["Michael Vancho", "Wed 4pm Court 8", ["Cole Evans", "Raustin Atefi", "LeRoy Nichols V", "Syon Sheth", "James Meshot", "Brayden Vancho", "Mason Silverman", "Aayansh Dave", "Max Wilson"]],
  "Celtics"=>["Glenn Wilkerson", "Mon 5pm Court 8", ["Gavin Wurster", "Brooks Howell", "Blake Bickoff", "Bodhi Firoved", "Harrison Porter", "Luke Shafer", "Hudson Brown", "Kai Wilkerson", "Andrew Nesheiwat"]]
], 
"2nd Grade"=>[
  "76ers"=>["Joe Cavallo", "Wed 4pm Court 7", ["Cameron Hill", "Christian Ballesteros", "James Cavallo", "Brody Nigg", "James Mitchell", "Landon Chandler", "Bryce Higgins", "Duke Sanders", "Grayson DAvanzo"]],
  "Clippers"=>["Mike Zhe", "Wed 5pm Court 7", ["Mikey Nelson", "Grady Waters", "Mason Zhe", "Crosby Gallicchio", "Millan Mehta", "Hudson Powell", "Nolan Reinmiller", "Tripp Juarez", "Nolan Stone"]],
  "Hornets"=>["Joe Fleskoski", "Tue 4pm Court 7", ["Case Vaughn", "Jackson Fleskoski", "Caden Peterson", "Rowan Brennan", "Hunter Larson", "Oliver Dimick", "Jesse Bahu", "Zaid Adas", "Bayyan Zeidan"]],
  "Celtics"=>["James Atterbury", "Wed 5pm Court 8", ["Hudson Howell", "Reed Steffenhagen", "Colin Brooks", "Noah Gyllenhammer", "Waylon Atterbury", "Parker McKay", "Wolf  Green", "Alexander Rados", "Aiden  Eberhardt"]],
  "Nuggets"=>["Brad Carter", "Mon 4pm Court 7", ["James Garrison", "Carter Bear", "Fisher Mullen", "Logan  Zdanek", "Nicholas Chantian", "Blake  Segal", "Trent  Davidson", "Finnley Carter", "Walt  Frank"]],
  "Warriors"=>["Walter Haldeman", "Wed 5pm Court 8", ["Daniel Freeman", "Bruce Haldeman", "Lyon Lamas-Ritchie", "Trey  Lopez", "Garrett Bell", "August Holstein", "Boston Allen", "Jojo McCulloch", "Hudson Allen", "Louis Ramirez"]],
  "Lakers"=>["Brian Morales", "Wed 5pm Court 7", ["Jack Kittredge", "Reed Morales", "Declan LeFever", "Zeke Larraga", "Ryan Taylor", "Caleb Donelson", "Roman LaSala", "Cameron Draude"]],
  "Bucks"=>["Lenny Fotopoulos", "Tue 4pm Court 7", ["Matteo Duenas", "Brooks Hudson", "John Fotopoulos", "Jack Pappas", "Roman Stronk", "Timmy Schaechterle", "Kosey  Ausar", "Drew  Vidovich", "Brody McConnell"]],
  "Jazz"=>["Jonathan Stevens", "Wed 4pm Court 7", ["Matix Rodriguez", "Brody Evans", "Stockton Stevens", "Collins MacLeod", "Curren Fennell", "Colin Currie", "Dean Davis", "Colin Komppa", "Rojer  Naid"]],
  "Timberwolves"=>["Ben Chamberlin", "Mon 4pm Court 7", ["Joshua Cook", "Dominic Fowler", "Brooks Pollack", "James Baca", "Leo Chamberlain", "Owen Baker", "Rayan Nabavi", "Carson Alexander", "John  Powers"]],
  "Rockets"=>["Eric Lilly", "Thu 4pm Court 8", ["Wes Lin", "Jordan Lin", "David Lilly", "Griffin Grant", "Finn Tanner", "Oliver Lilly", "Liam  Grenham", "Jordan Jund", "William Volk"]],
  "Knicks"=>["N/A", "Thu 4pm Court 8", ["Aaron Lavi", "Carter Stanfield", "Nate Sandor", "Joey Kanik", "Jaxson Williams", "Evan McManimen", "Easton Ha", "Maddox Melanio", "Greyson Ha"]]
], 
"3rd Grade"=>[
  "Rockets"=>["Nate Burnett", "Wed 5pm Court 7", ["Blake Burnett", "James Mock", "Payden Peetz", "Alex Zhou", "Josh Koenig", "Renzo Broas", "Bennet Koyfman", "Jordy Segal", "Jack Puffer", "Ryan Barsoum"]],
  "Hornets"=>["Bill Turner", "Wed 5pm Court 7", ["Enzo DiCaprio", "Beckett Turner", "Luca DeCaprio", "Luca Malanga", "Declan Branning", "Jet Fitzgerald", "Marvin Wang", "Ben Magnusson", "Liam Pappas", "Dylan Navarro"]],
  "Bucks"=>["Kevin Curtis", "Tues 5pm Court 7", ["Cruz Marcione", "Cayden Curtiss", "Weston Shearer", "Kayson Mesnick", "Shayan Nabavi", "Cooper Gray", "Austin Anderson", "Aviel Lavi", "Cole Gallicchio", "Noah Hayden"]],
  "Clippers"=>["Michael Nisenson", "Mon 5pm Court 7", ["Chase Nisenson", "Chase Bickoff", "Jake Rigdon", "Jacob McMahon", "Simon Christie", "Kainoa Parker", "Andrew Deen", "Knox Hoffman", "Henry Regalado", "Max Laraki"]],
  "Timerwolves"=>["Jordan Lewis", "Mon 5pm Court 7", ["Deyton Baker", "Ames Lewis", "Landon Hammerstone", "Taylor White", "Andrew Oots", "Cameron White", "Gavin Weber", "Owen Nickerson", "Connor Brown", "Walker Meinecke"]],
  "Lakers"=>["Scott Belnap", "Tues 5pm Court 7", ["Finn MacLachlan", "Jackson Davies", "Matthew Belnap", "Lucas Lee", "Ledger Smith", "Noah Alejandro Sander", "Jax Trounce", "Henry Nicholson", "Jamie Stopnitzky", "Vonn Zoller"]]
], 
"4th Grade"=>[
  "Hornets"=>["Steven Valdez", "Wed 6pm Court 8", ["Charlie Shaia", "Gavin Rosecrans", "Gunner Leaf", "Deacon Porsona", "Matt Magnusson", "Eamon King", "Benjamin Valdez", "Peter Tripi", "Lucas Barcelo"]],
  "Nuggets"=>["Ken Stark", "Wed 6pm Court 8", ["Lucas Yang", "Duke Johnson", "Cole Chandler", "Alexander Tsujiuchi", "Cole Wilkens", "Christopher Ibanez", "Gavin Fredericks", "Luke Packman", "Logan  Stark"]],
  "Rockets"=>["John Norton", "Mon 6pm Court 8", ["JP Mullen", "Kellen Bliss", "Sheffield Norton", "Hunter Schanna", "Santiago Haggard", "Smith Annett", "Bennett Matz", "Daniel Abdou", "Caleb Guiao"]],
  "Jazz"=>["Ben Chamberlin", "Mon 6pm Court 8", ["Xavier LaSala", "Jacob Donelson", "Hollis Waters", "Mason Chamberlin", "Eddie LaFever", "Hudson Bingham", "Cash Hada", "Jackson Chamberlin", "Hyrum Thomas"]],
  "Bucks"=>["Mike Hodson", "Wed 6pm Court 7", ["Joseph Fleskoski", "Oliver Nguyen", "Atticus Hodson", "Jackson Muckley", "Evan Mathews", "Evan Champagne", "Caden Cole", "Rayleigh Lopez", "Cayden Low"]],
  "Clippers"=>["Mike Guevara", "Thurs 5pm Court 5", ["Brant Shwam", "Samuel Guevara", "Asher Carpenter", "Hudson Smiley", "Beau Gaspar", "Benjamin Boulton", "Connor MacLeod", "Rowen Fernando", "Cal Leonard"]],
  "Timberwolves"=>["Craig Mackey", "Mon 6pm Court 5", ["Garren Mackey", "Blake Dragonetti", "Charlie Hudson", "Benjamin Dulik", "Carson Gardner", "Leo Gaya", "Filippo Bon", "Caden Leu", "Cruz Bartik"]],
  "Celtics"=>["N/A", "Mon 6pm Court 5", ["Maverick Wight", "Cash Skibba", "Matthew Sheen", "Mason Micek", "Owen Hills", "Jason Cook", "Canaan Steindler", "Elijah Mills", "Brylee Scott"]],
  "Knicks"=>["Corey McManimen", "Wed 6pm Court 7", ["Connor Jackson", "Benny Morales", "Clay Piscopo", "Dylan McManimen", "Ben Taylor", "Smith Lloyd-Catarra", "Carter Jund", "Jackson Willmunder", "Oliver Sterling"]],
  "Lakers"=>["Mike Junge", "Thurs 5pm Court 5", ["Marcel Polan", "Pierce  Bruggeman", "Colby Gartland", "Sean Ruppenthal", "Luke Horvatic", "Santiago Potes", "Grayson Governski", "Matty Junge", "Hammad Siddiqui"]]
], 
"5th/6th Grade"=>[
  "Timberwolves"=>["Jordan Lewis", "Mon 7pm Court 7", ["Owen Lewis", "Wyatt  Alexander", "Ammon Howell", "Bronz Davis", "Robby Piscopo", "Nixon Cook", "Henry Lewis", "Sander Schwind", "Noah Lewis", "Zaid Zeidan"]],
  "Celtics"=>["Darryl Cyphers", "Mon 7pm Court 7", ["Andres  Haggard", "Reed Tucker", "Jalen  Cyphers", "River Plumlee", "Conner  Nisenson", "Cole Martignetti", "Stellan  Erland", "Bode Williams", "Brayden Rosecrans", "Charles  Sternberg"]],
  "Rockets"=>["Ben Chamberlin", "Mon 7pm Court 5", ["Luke Chamberlain", "Jack Clottu", "Gage Marcione", "Suveer Koorapati", "Calder Allen", "Walker Atterbury", "Kevin Turul", "Aiden  Isakson", "Pietro Albanese", "Chase  Smith"]],
  "Bucks"=>["Manny Flores", "Thurs 5pm Court 8", ["Gavin Vorwerk", "Diego Flores", "Ryan Ripic", "Daven Du", "Liam Edwards", "Atticus Larraga", "Omid Balooch", "Graeson Gray", "Adam Mateo", "Max  Patterson"]],
  "Hornets"=>["Noah Larson", "Thur 5pm Court 8", ["Tyler Cierpial", "Riley Jackson", "AJ  Maroutsos", "Colton  Carlton", "Cristiano Bartik", "Dylan Ares", "Brady Larson", "Tyler Garcia Parsons", "Robby Ernst", "Emmitt  Moran"]],
  "Clippers"=>["Christian Rovsek", "Wed 6pm Court 5", ["Noah Williams", "Trenton Davis", "Kingston Rovsek", "Tyler Choan", "AJ Taylor", "Preston Rovsek", "Dylan Lim", "Lincoln Warhurst", "Ezra Jolley"]],
  "Jazz"=>["David Petersen", "Wed 6pm Court 5", ["Wyatt LeFever", "Nixon MacLachlan", "Cash Davis", "Ryan Petersen", "Luke Koyfman", "Gavin Hong", "Judah Reyes", "Jacob Navarro", "Zakai Cantrell", "Tristan Hendley"]],
  "Lakers"=>["Oscar Navarro", "Mon 7pm Court 5", ["Nathaniel Chai", "Tyson Tona", "Remy Trounce", "Braxton Jenkins", "True Youngash", "Zeff Dena", "Tristan Sorenson", "Giovanni Barcellona", "Levi Navarro", "Liam  Finder"]]
], 
"7th/8th Grade"=>[
  "Hornets"=>["Ryan Weller", "Tues 7:30pm Court 7", ["Cooper Chila", "Ryder Hoffman", "Gunnor Bachhuber", "Jack Weller", "Rush Davis", "Johnny Dordell", "Conor Fisher", "Joahl Santiago"]],
  "Clippers"=>["George Taylor", "Tues 7:30pm Court 7", ["Kaleb Taylor", "Landon Hammond", "Aidan Negrete", "Paul Gad", "AJ  Williams", "Raiden  Bhatia", "Keegan Fuhriman", "Frank Rodas"]],
  "Rockets"=>["Mike Rossi", "Wed 7pm Court 7", ["Jacadi Randle", "Scott Branning", "Michael Konstantarakis", "Ryan Hunt", "Naythan Kancharla", "John Konstantarakis", "Leo Assalian", "Dylan Rossi"]],
  "Lakers"=>["Jason Mericle", "Wed 7pm Court 7", ["Cooper Hladek", "Ryan Stratton", "Kaikoa Truong", "Jax Jensen", "Sebastian Flores", "Quentin Letcher", "Spandan  Ghosh", "Hunter Mericle"]],
  "Bucks"=>["Spencer Hamer", "Wed 7pm Court 8", ["Matthew Bernardo", "Maxwell Porter", "Glenn Gordon", "Evan Ditto", "Spencer Pirtle", "Nicholas Hamer", "Ozan David Kose-Montoya", "Finn Petar", "Braxton Landsman"]],
  "Timberwolves"=>["Bob Viscount", "Wed 7pm Court 8", ["Cooper Waters", "Wyatt Guerra", "Evan Colon", "Diego Camacho", "Jacob Abdou", "Ryder Whitcomb", "Blake Viscount", "Breckin Padilla", "Mark Britt"]]
]];
$girls_divisions = ["Girls K-2"=>[
  "Sparks"=>["Damien Pirro", "N/A", ["Isabella Webb", "Meyer Foust", "Tate Horvatic", "Gianna Kanik", "Emma King", "Kaylee Musser", "Emily Pirro", "Sophia Pirro"]],
  "Sun"=>["Mike Jensen", "Tue 5pm Court 5", ["Jade Blackmon", "Mila Coleman", "Charlotte Ferraro", "Savannah Geer", "Charlotte Hougland", "Avalon Jensen", "Reagan Mackey"]],
  "Storm"=>["James Decker", "Tue 5pm Court 5", ["Elise Kariuki", "Emma Berkley", "Matilda Brandon", "Mia Brooks", "Cece Decker", "Isla Fisher", "Violette Raubolt"]],
  "Sky"=>["Don Bunnin", "Mon 4pm Court 5", ["Brielle Bennett", "Brielle Bloom", "Vivienne Bunnin", "Tatum Gahagan", "Myla Nebot", "Anna Rymsza", "Reese Tremblay", "Annie Weller"]]
], 
"Girls 3rd-5th"=>[
  "Sky"=>["Lisa Zollinger", "Mon 6pm Court 7", ["Amelia Green", "Avery Hick", "Payton Johnson", "Harper Mokede", "Addison Ruziecki", "Isabella Skorheim", "Piper Theberge", "Annika Tootikian", "Bria Wilkerson", "Janie Zollinger"]],
  "Spark"=>["Don Bunnin", "Mon 6pm Court 7", ["Olivia Bell", "Mia  Boussaa", "Lara Brandt", "Colette Bunnin", "Kaila Fukunaga", "Izzy Gallicchio", "Taylor Goodwin", "Emma Menchaca", "Aanya Nalajala", "Olivia Silva"]],
  "Storm"=>["Albert Hsueh", "Tue 5pm Court 8", ["Lydia Camacho", "Cici Hernandez", "Kate Hsueh", "Elle Leonard", "Maren Padilla", "Isabel Ramirez", "Lexi Shood", "Sophia Thomas", "Isabel Tremblay", "Avery Vorwerk"]],
  "Sun"=>["Johnathan Civita", "Tue 5pm Court 8", ["Violet Brady", "Callie Civita", "Valerie Jasso", "Claire Lau", "Gabrielle McNaughton", "Brixtyn Nichols", "Gabbi Parham", "Natalie Russell", "Bailey Wang", "Jaime Yu"]]
], 
"Girls 6th-8th"=>[
  "Storm"=>["Bill Engel", "Mon 7pm Court 8", ["Hailey Barber", "Ashlyn Engel", "Cadence Henss", "Eleanor Missbach", "Hanna  Spongberg", "Karly Tindell", "Kate Wiacek"]],
  "Sun"=>["Ryan Weller", "Mon 7pm Court 8", ["Ava Boussaa", "Coco Conk", "Brooklyn Dillon", "Scotland Moss", "Perry Sudakoff", "Lily Weller"]],
  "Sparks"=>["Jen Waters", "Wed 7pm Court 5", ["Lyla Baldridge", "Georgia Ferrell", "Stella Grant", "Anabelle Kobus", "Olivia Matz", "Kaia Unell", "Presley Waters"]],
  "Sky"=>["Clay Thomas", "Wed 7pm Court 5", ["Mylee Farnsworth", "Madeline Hales", "Addison Hossfeld", "Brinley Johnson", "Juliet Ramirez", "Ava Schneider", "Elyse Thomas"]]
]];
foreach($boys_divisions as $index => $boys_division){
  foreach($boys_division as $dex => $division_rosters){
    sort($boys_divisions[$index][$dex][2]);
  } 
}
foreach($girls_divisions as $index => $girls_division){
  foreach($girls_division as $dex => $division_rosters){
    sort($girls_divisions[$index][$dex][2]);
  } 
}
?>
<h3>OGP Ladera Youth Basketball League Winter 2022-23 Rosters</h3>
<ul class="tabs" data-active-collapse="true" data-tabs id="collapsing-tabs">
  <li class="tabs-title is-active"><a href="#main_boys_divisions" aria-selected="true">Boys Divisions</a></li>
  <li class="tabs-title"><a href="#main_girls_divisions">Girls Divisions</a></li>
</ul>
<div class="tabs-content" data-tabs-content="collapsing-tabs">
  <div class="tabs-panel is-active" id="main_boys_divisions">
    <ul class="tabs" data-active-collapse="true" data-tabs id="collapsing-tabs">
      <?php foreach($boys_divisions as $index => $boys_division): if($index == 'Kindergarten'){ $is_active = "is-active"; $aria = "true"; $tabindex = "0"; }else{ $is_active = ""; $aria = "false"; $tabindex = "-1"; } ?>
        <li class="tabs-title <?php echo $is_active; ?>"><a href="#<?php echo strtolower(str_replace(array(' ', '/'), array('_', '_'), $index)); ?>" aria-selected="<?php echo $aria; ?>" tabindex="<?php echo $tabindex; ?>"><?php echo $index; ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="tabs-content" data-tabs-content="collapsing-tabs">
      <?php foreach($boys_divisions as $index => $boys_division): if($index == 'Kindergarten'){ $is_active = "is-active"; $aria = "true"; $tabindex = "0"; }else{ $is_active = ""; $aria = "false"; $tabindex = "-1"; } ?>
        <div class="tabs-panel <?php echo $is_active; ?>" id="<?php echo strtolower(str_replace(array(' ', '/'), array('_', '_'), $index)); ?>">
          <div class="grid-x">
          <?php foreach($boys_division as $dex => $boys_dv_data): ?>
            <div class="large-4">
              <ul>
                <?php echo "<h5>".$dex."</h5>"; foreach($boys_dv_data as $index => $boys_dv_lists): if(is_numeric($index) && $index < 2): 
                  if($index == 0){ $label = 'Head Coach: '; }
                  elseif($index == 1){ $label = 'Practices: '; }
                ?>
                  <li><?php echo $label; print_r($boys_dv_lists); ?></li>
                <?php endif; endforeach; echo "<label style='font-size: 16px; text-decoration: underline;font-weight: bold;'>Roster</label>"; foreach($boys_dv_data[2] as $roster_data){ echo "<li>"; print_r($roster_data); echo "</li>"; } ?>
              </ul>
            </div>
          <?php endforeach ?>
        </div></div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="tabs-panel" id="main_girls_divisions">
    <ul class="tabs" data-active-collapse="true" data-tabs id="collapsing-tabs">
      <?php foreach($girls_divisions as $index => $girls_division): if($index == 'Girls K-2'){ $is_active = "is-active"; $aria = "true"; $tabindex = "0"; }else{ $is_active = ""; $aria = "false"; $tabindex = "-1"; } ?>
        <li class="tabs-title"><a href="#<?php echo strtolower(str_replace(array(' ', '/'), array('_', '_'), $index)); ?>" aria-selected="true"><?php echo $index; ?></a></li>
      <?php endforeach; ?>
    </ul>
    <div class="tabs-content" data-tabs-content="collapsing-tabs">
      <?php foreach($girls_divisions as $index => $girls_division): if($index == 'Girls K-2'){ $is_active = "is-active"; $aria = "true"; $tabindex = "0"; }else{ $is_active = ""; $aria = "false"; $tabindex = "-1"; } ?>
        <div class="tabs-panel" id="<?php echo strtolower(str_replace(array(' ', '/'), array('_', '_'), $index)); ?>">
          <div class="grid-x">
          <?php foreach($girls_division as $dex => $girls_dv_data): ?>
            <div class="large-4">
              <ul>
                <?php echo "<h5>".$dex."</h5>"; foreach($girls_dv_data as $index => $girls_dv_lists): if(is_numeric($index) && $index < 2): 
                  if($index == 0){ $label = 'Head Coach: '; }
                  elseif($index == 1){ $label = 'Practices: '; }
                ?>
                  <li><?php echo $label; print_r($girls_dv_lists); ?></li>
                <?php endif; endforeach; echo "<label style='font-size: 16px; text-decoration: underline;font-weight: bold;'>Rosters</label>"; foreach($girls_dv_data[2] as $roster_data){ echo "<li>"; print_r($roster_data); echo "</li>"; } ?>
              </ul>
            </div>
          <?php endforeach ?>
        </div></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>
</section>