<?php
$attribute_taxonomies = wc_get_attribute_taxonomies();
$attributes = [];
foreach ($attribute_taxonomies as $taxonomy) {
    $attributes[] = 'pa_' . $taxonomy->attribute_name;
}

if ($attributes) {
    echo '<form id="product-filters" class="flex flex-wrap gap-4 mb-8 items-center">';
    foreach ($attributes as $attribute) {
        $terms = get_terms($attribute);
        if ($terms) {
            echo '<div class="dropdown">';
            echo '<div tabindex="0" role="button" class="btn m-1">' . wc_attribute_label($attribute) . '</div>';
            echo '<ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">';
            foreach ($terms as $term) {
                echo '<li><label class="label cursor-pointer"><span class="label-text">' . $term->name . '</span><input type="checkbox" name="' . $attribute . '[]" value="' . $term->slug . '" class="checkbox checkbox-primary" /></label></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    echo '<button type="button" id="reset-filters" class="btn btn-dash btn-info hidden">Réinitialiser</button>';
    echo '</form>';
}
