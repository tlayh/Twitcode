<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\RoutePartHandlers;

/**
 * Standard route part handler
 *
 * http://flow3.typo3.org/documentation/tutorials/getting-started/gettingstarted.routing/
 *
 * @author Thomas Layh <thomas@layh.com>
 * @scope prototype
 */
class StandardRoutePartHandler extends \F3\FLOW3\MVC\Web\Routing\DynamicRoutePart {

	/**
	 * Splits the given value into the date and title of the post and sets this
	 * value to an identity array accordingly.
	 *
	 * @param string $value The value (ie. part of the request path) to match. This string is rendered by findValueToMatch()
	 * @return boolean TRUE if the request path formally matched
	 */
	protected function matchValue($value) {
		if (!parent::matchValue($value)) {
			return FALSE;
		}
		$value = substr($value, 0, strpos($value, '/'));
		$this->value = array(
			'__identity' => array(
				'uid' => intval($value)
			)
		);
		return TRUE;
	}

	/**
	 * Checks if the remaining request path starts with the path signature of a post, which
	 * is: YYYY/MM/DD/TITLE eg. 2009/03/09/my-first-blog-entry
	 *
	 * If the request path matches this pattern, the matching part is returned as the "value
	 * to match" for further processing in matchValue(). The remaining part of the requestPath
	 * (eg. the format ".html") is ignored.
	 *
	 * @param string $requestPath The request path acting as the subject for matching in this Route Part
	 * @return string The post identifying part of the request path or an empty string if it doesn't match
	 */
	protected function findValueToMatch($requestPath) {
		return $requestPath;
	}

	/**
	 * Resolves the name of the post
	 *
	 * @param \F3\Twticode\Domain\Model\Code $value The Code object
	 * @return boolean TRUE if the post could be resolved and stored in $this->value, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		if (!$value instanceof \F3\Twitcode\Domain\Model\Code) return FALSE;
		$this->value = $value->getUid();

		// prepare label for url
		$label = strtolower(str_replace(' ', '-', $value->getLabel()));
		$label = preg_replace('/[^0-9a-zA-Z-_]/', '', $label);
		$this->value .= '/'.$label;


		return TRUE;
	}

}
