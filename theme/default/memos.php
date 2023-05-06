<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Memos
 *
 * @package custom
 * @link https://github.com/fengqi/typecho-memos
 */

use Utils\Markdown;

$options = Typecho_Widget::widget('Widget_Options');
$page_size = intval($options->plugin('Memos')->page_size);
$open_api = $options->plugin('Memos')->open_api;
$visibility = $options->plugin('Memos')->visibility;

if (!$open_api || !$page_size || !$visibility) exit('未配置open api');

$page = (!isset($_REQUEST['page']) || $_REQUEST['page'] <= 1) ? 1 : $_REQUEST['page'];
$limit = $page_size > 0 ? $page_size : 20;
$prev_page = $page - 1 <= 0 ? 1 : $page - 1;
$next_page = $page + 1;
$offset = ($page -1) * $limit;

$now = time();
$cacheTime = 300;
$content = '';
$file = '/tmp/typecho_memos_cache_'.$page.'_'.$visibility.'_'.$limit;
if (is_file($file)) {
    $content = file_get_contents($file);
    $_time = substr($content, 0, 10);
    $content = json_decode(substr($content, 10));

    if ($now > $_time) $content = '';
}

if (!$content) {
    $data = [
        'visibility' => $visibility == 'PUBLIC' ? '' : $visibility,
        'limit' => $limit,
        'offset' => $offset,
        'tag' => '',
    ];
    $ch = curl_init($open_api . '&' . http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $content = curl_exec($ch);
    curl_close($ch);

    file_put_contents($file, ($now + $cacheTime).$content);
    $content = json_decode($content);
}

function pickTag($content)
{
    $pattern = '/#([^\s#]+)/';
    if (preg_match_all($pattern, $content, $match)) {
        return $match[1];
    }
    return [];
}

function markdown($content)
{
    $tags = pickTag($content);
    foreach ($tags as $tag) {
        $content = str_replace("#$tag", "[$tag](?tag=$tag)", $content);
    }

    return Markdown::convert($content);;
}

?>

<?php $this->need('header.php'); ?>

<div class="col-mb-12 col-8" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
    </article>

    <?php foreach($content->data as $item): ?>
        <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <ul class="post-meta">
            <li>时间: <time datetime="<?php echo date('Y-m-d H:i:s', $item->createdTs) ?>" itemprop="datePublished"><?php echo date('Y-m-d H:i:s', $item->createdTs) ?></time></li>
            <?php $tags = pickTag($item->content); ?>
            <?php if (!empty($tags)): ?>
                <li>标签: <?php echo implode(', ', $tags); ?></li>
            <?php endif; ?>
        </ul>

        <div class="post-content" itemprop="articleBody">
            <?php echo markdown($item->content) ?>
        </div>
    </article>
    <?php endforeach; ?>

    <ol class="page-navigator">
        <?php if($page != 1): ?>
            <li class="prev"><a href="<?php $this->permalink() ?>?page=<?php echo $prev_page ?>">« 前一页</a></li>
        <?php else: ?>
            <li class="next">« 前一页</li>
        <?php endif; ?>
        <?php if($content && count($content->data) == $limit): ?>
            <li class="next"><a href="<?php $this->permalink() ?>?page=<?php echo $next_page ?>">后一页 »</a></li>
        <?php else: ?>
            <li class="next">后一页 »</li>
        <?php endif; ?>
    </ol>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
