# kevinruscoe/google-reviews

A simple wrapper to get Google reivews from the Places API.

Because Google limits the amount of API calls to their Places API, this cache's the result for 24 hours.

```
include "vendor/autoload.php";

$apiKey = "WHATEVER";
$placeId = "WHATEVER";

var_dump(
    (new KevinRuscoe\GoogleReviews($apiKey))
        ->getReviewsForPlace($placeId)
);
```