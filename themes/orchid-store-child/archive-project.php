<?php get_header() ?>
<h1>Danh sách dự án</h1>
<?php
/**
 * the_post(); Hàm này lấy dữ liệu của bài viết hiện tại và thiết lập nó 
 * để có thể sử dụng các hàm hiển thị nội dung như the_title(), the_content(), v.v.
 * the_permalink(): Trả về URL của bài viết.
 * the_title(): Hiển thị tiêu đề bài viết.
 */
if (have_posts()) : while (have_posts()) : the_post(); ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php endwhile;
else: ?>
    <p>Không có dự án nào.</p>

<?php endif;
get_footer() ?>