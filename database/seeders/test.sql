select
    `posts`.*,
    `subcategories`.`subcategory_title_en`,
    `subcategories`.`subcategory_title_bg`,
    `categories`.`category_id`,
    `categories`.`category_title_en`,
    `categories`.`category_title_bg`,
    `users`.`name`,
    `users`.`city_id`,
    `users`.`user_type`,
    `cities`.`city_id`,
    `cities`.`city_title_en`,
    `cities`.`city_title_bg`,
    `divisions`.`division_id`,
    `divisions`.`division_title_en`,
    `divisions`.`division_title_bg`,
    `postimages`.`postimage_thumbnail`
from
    `posts`
    inner join `subcategories` on `subcategories`.`subcategory_id` = `posts`.`subcategory_id`
    inner join `categories` on `categories`.`category_id` = `subcategories`.`parent_category_id`
    inner join `users` on `users`.`id` = `posts`.`user_id`
    inner join `cities` on `cities`.`city_id` = `users`.`city_id`
    inner join `divisions` on `divisions`.`division_id` = `cities`.`division_id`
    inner join `postimages` on `postimages`.`post_id` = `posts`.`post_id`
where
    `users`.`account_status` = 1
    and `posts`.`status` = 1
group by
    `postimages`.`post_id`
order by
    `posts`.`views` desc
limit
    3
