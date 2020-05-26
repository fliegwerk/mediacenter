<?php
/*
 * fliegwerk mediacenter
 * by fliegwerk
 * (c) 2020. MIT License
 */


/*
 * Creates an array with all substrings which length is greater than 2
 */
function sub_strings($str) {
    $sub_strings = array();
    $length = strlen($str);
    for ($i = 0; $i < $length; $i++) {
        for ($j = 0; $j < $length; $j++) {
            $sub_string = substr($str, $i, $j);
            if (strlen($sub_string) > 2) {
                $sub_strings[] = $sub_string;
            }
        }
    }
    if (empty($sub_strings)) $sub_strings[] = $str;
    return $sub_strings;
}

/*
 * Scores an option string with given query substrings
 */
function score_option($query_sub_strings, $option_string) {
    if (empty($option_string)) return 0;
    $score = 0;

    foreach ($query_sub_strings as $query_sub_string) {
        $lower_query_sub_string = strtolower($query_sub_string);
        $lower_option_string = strtolower($option_string);

        $score += (count(explode($lower_query_sub_string, $lower_option_string)) - 1)
            * (strlen($lower_query_sub_string) ** 2);
    }

    $score /= strlen($option_string);
    $score = atan($score);
    return $score;
}

/*
 * sorts and filters given options with a query string
 */
function search($options, $query) {
    if (empty($options) or empty($query)) return $options;
    $query_sub_strings = sub_strings($query);
    $scored_options = array();
    // get option scores and packing with option
    foreach ($options as $option) {
        $score = score_option($query_sub_strings, $option->title);
        $scored_options[] = (object)[
            "option" => $option,
            "score" => $score,
        ];
    }
    // filter option scores > 0
    $scored_options = array_filter($scored_options, function($elem) {
        return $elem->score > 0;
    });
    // sort by greater score (reverse order)
    usort($scored_options, function($a, $b) {
        if ($a->score < $b->score) return 1;
        if ($a->score > $b->score) return -1;
        return 0;
    });
    // extract option
    return array_map(function($elem) {
        return $elem->option;
    }, $scored_options);
}

?>
