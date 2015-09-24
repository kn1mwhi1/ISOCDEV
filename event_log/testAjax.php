<html>
<head>
<script src="script/jquery.js"></script>

<script type="text/javascript">
$("document").ready(function(){
  $(".js-ajax-php-json").submit(function(){
    var data = {
      "action": "test"
    };
    data = $(this).serialize() + "&" + $.param(data);
    $.ajax({
      type: "POST",
      dataType: "json",
      url: "postTest.php", //Relative or absolute path to postTest.php file
      data: data,
      success: function(data) {
        $(".the-return").html(
          "Favorite beverage: " + data["favorite_beverage"] + "<br />Favorite restaurant: " + data["favorite_restaurant"] + "<br />Gender: " + data["gender"] + "<br />JSON: " + data["json"]
        );

        alert("Form submitted successfully.\nReturned json: " + data["json"]);
      }
    });
    return false;
  });
});
</script>

</head>


<body>

<form action="testAjax.php" class="js-ajax-php-json" method="post" accept-charset="utf-8">
  <input type="text" name="favorite_beverage" value="" placeholder="Favorite restaurant" />
  <input type="text" name="favorite_restaurant" value="" placeholder="Favorite beverage" />
  <select name="gender">
    <option value="male">Male</option>
    <option value="female">Female</option>
  </select>
  <input type="submit" name="submit" value="Submit form"  />
</form>

<div class="the-return">
  [HTML is replaced when successful.]
</div>




</body>
</html>