<?php
namespace jdpowered\EasyDebugInfo\Reporters;

use jdpowered\EasyDebugInfo\Contracts\Reporter;

class StatisticsReporter extends BaseReporter implements Reporter {

    /**
     * Return the name of the reporter
     *
     * @return string
     */
    public function getName()
    {
        return 'Statistics';
    }

    /**
     * Return the description of the reporter
     *
     * @return string
     */
    public function getDescription()
    {
        return __('Various statistics, includeing the number of posts, pages, comments, users, etc.', 'easydebuginfo');
    }

    /**
     * Do investigations and return report
     *
     * @return array
     */
    public function report()
    {
        $this->postsReport();
        $this->pagesReport();
        $this->commentsReport();
        $this->categoriesReport();
        $this->tagsReport();
        $this->usersReport();
        return $this->lines;
    }

    /**
     * Generate posts report
     *
     * @since 1.0.0
     */
    protected function postsReport()
    {
        $this->addHeadingLine('Posts');
        $this->addLabeledLine('Published', $this->getPostsCount('publish'));
        $this->addLabeledLine('Planned', $this->getPostsCount('future'));
        $this->addLabeledLine('Drafts', $this->getPostsCount('draft'));
        $this->addLabeledLine('Autosaves', $this->getPostsCount('auto-draft'));
        $this->addLabeledLine('Trashed', $this->getPostsCount('trash'));
    }

    /**
     * Generate pages report
     *
     * @since 1.0.0
     */
    protected function pagesReport()
    {
        $this->addHeadingLine('Pages');
        $this->addLabeledLine('Published', $this->getPagesCount('publish'));
        $this->addLabeledLine('Planned', $this->getPagesCount('future'));
        $this->addLabeledLine('Drafts', $this->getPagesCount('draft'));
        $this->addLabeledLine('Autosaves', $this->getPagesCount('auto-draft'));
        $this->addLabeledLine('Trashed', $this->getPagesCount('trash'));
    }

    /**
     * Generate comments report
     *
     * @since 1.0.0
     */
    protected function commentsReport()
    {
        $this->addHeadingLine('Comments');
        $this->addLabeledLine('Approved', $this->getCommentsCount('approved'));
        $this->addLabeledLine('In Moderation', $this->getCommentsCount('moderated'));
        $this->addLabeledLine('Spam', $this->getCommentsCount('spam'));
        $this->addLabeledLine('Trashed', $this->getCommentsCount('trash'));
        $this->addLabeledLine('Total', $this->getCommentsCount('total_comments'));
    }

    /**
     * Generate categories report
     *
     * @since 1.0.0
     */
    protected function categoriesReport()
    {
        $this->addHeadingLine('Categories');
        $this->addLabeledLine('Total', $this->getCategoryCount());
    }

    /**
     * Generate tags report
     *
     * @since 1.0.0
     */
    protected function tagsReport()
    {
        $this->addHeadingLine('Tags');
        $this->addLabeledLine('Total', $this->getTagCount());
    }

    /**
     * Generate users report
     *
     * @since 1.0.0
     */
    protected function usersReport()
    {
        /* TODO: Handle custom user roles */
        $this->addHeadingLine('Users');
        $this->addLabeledLine('Administrators', $this->getUserCount('administrator'));
        $this->addLabeledLine('Editors', $this->getUserCount('editor'));
        $this->addLabeledLine('Authors', $this->getUserCount('author'));
        $this->addLabeledLine('Contributers', $this->getUserCount('contributor'));
        $this->addLabeledLine('Subscribers', $this->getUserCount('subscriber'));
        $this->addLabeledLine('Total', $this->getUserCount('total'));
    }

    /**
     * Get the number of elements available for a post type
     *
     * @since 1.0.0
     *
     * @param  string $postType
     * @param  string $filter
     * @return int
     */
    protected function getPostTypeCount($postType, $filter)
    {
        $counts = wp_count_posts($postType, 'readable');
        return (isset($counts->{$filter})) ? $counts->{$filter} : 0;
    }

    /**
     * Get the number of posts
     *
     * @since 1.0.0
     *
     * @param  string $filter
     * @return int
     */
    protected function getPostsCount($filter)
    {
        return $this->getPostTypeCount('post', $filter);
    }

    /**
     * Get the number of pages
     *
     * @since 1.0.0
     *
     * @param  string $filter
     * @return int
     */
    protected function getPagesCount($filter)
    {
        return $this->getPostTypeCount('page', $filter);
    }

    /**
     * Get the number of comments
     *
     * @since 1.0.0
     *
     * @param  string $filter
     * @return int
     */
    protected function getCommentsCount($filter)
    {
        $counts = wp_count_comments();
        return (isset($counts->{$filter})) ? $counts->{$filter} : 0;
    }

    /**
     * Get the number of elements for a taxonomy
     *
     * @since 1.0.0
     *
     * @param  string $taxonomy
     * @return int
     */
    protected function getTaxonomyCount($taxonomy)
    {
        return wp_count_terms($taxonomy, array('hide_empty' => false));
    }

    /**
     * Get the number of categories
     *
     * @since 1.0.0
     *
     * @return int
     */
    protected function getCategoryCount()
    {
        return $this->getTaxonomyCount('category');
    }

    /**
     * Get the number of categories
     *
     * @since 1.0.0
     *
     * @return int
     */
    protected function getTagCount()
    {
        return $this->getTaxonomyCount('post_tag');
    }

    /**
     * Get the number of users
     *
     * @since 1.0.0
     *
     * @param string $filter
     * @return int
     */
    protected function getUserCount($filter)
    {
        $counts = count_users();

        if($filter == 'total')
            return $counts['total_users'];

        return (isset($counts['avail_roles'][$filter])) ? $counts['avail_roles'][$filter] : 0;
    }

}
