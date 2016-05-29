<?php

  $city = (isset($_GET['city'])) ? $_GET['city'] : null;
  $state = (isset($_GET['state'])) ? $_GET['state'] : null ;
  $zip = (isset($_GET['zip']))? $_GET['zip'] : null;

  if (!empty($city)) {
    $type = 'CITY';
    $city = substr(trim(filter_var($city, FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW])),0,25);
  } elseif (!empty($zip)) {
    $type = 'ZIP';
    $zip = substr(filter_var($zip, FILTER_SANITIZE_NUMBER_INT),0,10);
  } else {
    $type = 'STATE';
    $state = strtoupper(filter_var(substr(trim($state),0,2), FILTER_SANITIZE_STRING, [FILTER_FLAG_STRIP_HIGH,FILTER_FLAG_STRIP_LOW]));
  }

  echo $type.' '.$city;

  //execute sql based on command
  $con=mysqli_connect(MY_DB_HOST,MY_DB_USER,MY_DB_PASSWORD,MY_DB_DATABASE);
  //if zip then get user's long + lat

  $sql = "
    select distinct
        u.id
        , u.fname
        , u.lname
        , c.gym_name
        , c.gym_address
        , c.gym_city
        , c.gym_state
        , c.gym_zip
    from ".MY_MEMBER_DB_TABLE." u
    inner join ".MY_MEMBER_CLASS_DB_TABLE." c on u.id = c.user_id
    inner join ".MY_ZIP_DB_TABLE." z on c.gym_zip = z.zipcode
    where u.status = 1
  ";
//	--and (z.Latitude BETWEEN ?-2 and ?+2) and (z.Longitude BETWEEN ?-2 and ?+2)

//append based on filter
  switch($type) {
    case 'STATE':
      $sql .= ' and c.gym_state = ?';
    break;

    case 'CITY':
      $sql .= " and c.gym_city like ?";
    break;

    default:

  }

  //order by clause



  $stmt = $con->prepare($sql);

  //append based on filter
  switch($type) {
    case 'STATE':
      $stmt->bind_param("s", $state);
      break;

    case 'CITY':
      $city = '%'.$city.'%';
      $stmt->bind_param("s", $city);
      break;

    default:

  }

echo $sql;

  $stmt->execute();
  $stmt->bind_result($id, $fname, $lname, $gym_name, $gym_address, $gym_city, $gym_state, $gym_zip);
  $stmt->store_result();


  while ($stmt->fetch()) {
    echo $id.' '.$fname.' '.$gym_state.' '.$gym_city.'<br>';

  }

?>
<div class="twelve columns">
  <?php
    switch(get_the_ID ()):
    case 39: //search
  ?>
    <article>
      <?php the_content(); ?>
    </article>

    <div class="search_forms_container">
      <form action="/find-a-class/find-a-cass-list/" method="get">
        <input type="text" placeholder="Search by city" name="city">
        <input type="submit" value="">
      </form>

      <form action="/find-a-class/find-a-cass-list/" method="get">
        <select name="state">
          <option value="">Select A State</option>
          <option value="AL">Alabama</option>
          <option value="AK">Alaska</option>
          <option value="AZ">Arizona</option>
          <option value="AR">Arkansas</option>
          <option value="CA">California</option>
          <option value="CO">Colorado</option>
          <option value="CT">Connecticut</option>
          <option value="DE">Delaware</option>
          <option value="DC">District Of Columbia</option>
          <option value="FL">Florida</option>
          <option value="GA">Georgia</option>
          <option value="HI">Hawaii</option>
          <option value="ID">Idaho</option>
          <option value="IL">Illinois</option>
          <option value="IN">Indiana</option>
          <option value="IA">Iowa</option>
          <option value="KS">Kansas</option>
          <option value="KY">Kentucky</option>
          <option value="LA">Louisiana</option>
          <option value="ME">Maine</option>
          <option value="MD">Maryland</option>
          <option value="MA">Massachusetts</option>
          <option value="MI">Michigan</option>
          <option value="MN">Minnesota</option>
          <option value="MS">Mississippi</option>
          <option value="MO">Missouri</option>
          <option value="MT">Montana</option>
          <option value="NE">Nebraska</option>
          <option value="NV">Nevada</option>
          <option value="NH">New Hampshire</option>
          <option value="NJ">New Jersey</option>
          <option value="NM">New Mexico</option>
          <option value="NY">New York</option>
          <option value="NC">North Carolina</option>
          <option value="ND">North Dakota</option>
          <option value="OH">Ohio</option>
          <option value="OK">Oklahoma</option>
          <option value="OR">Oregon</option>
          <option value="PA">Pennsylvania</option>
          <option value="RI">Rhode Island</option>
          <option value="SC">South Carolina</option>
          <option value="SD">South Dakota</option>
          <option value="TN">Tennessee</option>
          <option value="TX">Texas</option>
          <option value="UT">Utah</option>
          <option value="VT">Vermont</option>
          <option value="VA">Virginia</option>
          <option value="WA">Washington</option>
          <option value="WV">West Virginia</option>
          <option value="WI">Wisconsin</option>
          <option value="WY">Wyoming</option>
        </select>
        <input type="submit" value="">
      </form>

      <form action="/find-a-class/find-a-cass-list/" method="get">
        <input type="text" placeholder="Search by zip code" name="zip">
        <input type="submit" value="">
      </form>
    </div>

  <?php
      break;
      case 5968: //list
  ?>
  <div class="row">
    <div class="twelve columns">
      <table class="event_table teach find_a_class">
        <tr>
          <th>Date & Time</th>
          <th>Gym Name</th>
          <th>State</th>
          <th>Address</th>
          <th>Register</th>
        </tr>
      </table>
    </div>
  </div>


  <?php
      break;
      case 5970:
  ?>

  this is 6970

  <?php
    break;
  endswitch;
  ?>

</div>