<?php
/**
 * Taken from :
 * https://github.com/MartinThoma/algorithms/tree/master/crossingLineCheck
 * http://martin-thoma.com/how-to-check-if-two-line-segments-intersect/#Where_do_two_line_segments_intersect
 */

/**
 * Check if the two segment specified doLinesIntersect in interesect
 */
class LineIntersectionChecker {

    const EPSILON = 0.000001;

    /**
     * Calculate the cross product of two points.
     * @param $a first point
     * @param $b second point
     * @return the value of the cross product
     */
    public static function crossProduct($a, $b) {
        return $a['x'] * $b['y'] - $b['x'] * $a['y'];
    }

    /**
     * Check if bounding boxes do intersect. If one bounding box
     * touches the other, they do intersect.
     * @param array $boxA first bounding box
     * @param array $boxB second bounding box
     * @return <code>true</code> if they intersect,
     *         <code>false</code> otherwise.
     */
    public static function doBoundingBoxesIntersect($boxA, $boxB) {
        return $boxA['lower-left']['x'] <= $boxB['top-right']['x'] 
        && $boxA['top-right']['x'] >= $boxB['lower-left']['x']
        && $boxA['lower-left']['y'] <= $boxB['top-right']['y'] 
        && $boxA['top-right']['y'] >= $boxB['lower-left']['y'];
    }

    /**
     * Checks if a Point is on a line
     * @param array $sa line (interpreted as line, although given as line
     *                segment)
     * @param array $b point
     * @return <code>true</code> if point is on line, otherwise
     *         <code>false</code>
     */
    public static function isPointOnLine($sa, $b) {
        // Move the image, so that a.first is on (0|0)
         $aTmp = array(  'start' => array('x' => 0, 'y' => 0),
                        'end' => array('x' => $sa['end']['x'] - $sa['start']['x'], 'y' => $sa['end']['y'] - $sa['start']['y']),
                    );

        $bTmp = array('x' => $b['x']  - $sa['start']['x'], 'y' => $b['y'] - $sa['start']['y']);

        $r = self::crossProduct($aTmp['end'], $bTmp);
        return abs($r) < self::EPSILON;
    }

    /**
     * Checks if a point is right of a line. If the point is on the
     * line, it is not right of the line.
     * @param array $sa line segment interpreted as a line
     * @param array $b the point
     * @return <code>true</code> if the point is right of the line,
     *         <code>false</code> otherwise
     */
    public static function isPointRightOfLine($sa, $b) {
        // Move the image, so that $sa['start'] is on (0|0)
        $aTmp = array(  'start' => array('x' => 0, 'y' => 0),
                        'end' => array('x' => $sa['end']['x'] - $sa['start']['x'], 'y' => $sa['end']['y'] - $sa['start']['y']),
                    );

        $bTmp = array('x' => $b['x']  - $sa['start']['x'], 'y' => $b['y'] - $sa['start']['y']);

        return self::crossProduct($aTmp['end'], $bTmp) < 0;
    }

    /**
     * Check if line segment first touches or crosses the line that is
     * defined by line segment second.
     *
     * @param array $sa first line segment interpreted as line
     * @param array $sa second line segment
     * @return <code>true</code> if line segment first touches or
     *                           crosses line second,
     *         <code>false</code> otherwise.
     */
    public static function lineSegmentTouchesOrCrossesLine($sa,$sb) {
        return self::isPointOnLine($sa, $sb['start'])
                || self::isPointOnLine($sa, $sb['end'])
                || (self::isPointRightOfLine($sa, $sb['start']) ^ self::isPointRightOfLine($sa, $sb['end']));
    }

     /**
    * Get the bounding box of this line by two points. The first point is in
    * the lower left corner, the second one at the upper right corner.
    *
    * @param array $sa the line segment
    * @return array the bounding box
    */
    public static function getBoundingBox($sa) {
        $result = array();
       
        $result['lower-left'] = array('x' => min($sa['start']['x'], $sa['end']['x']), 'y' => min($sa['start']['y'], $sa['end']['y']));
        $result['top-right'] = array('x' => max($sa['start']['x'], $sa['end']['x']), 'y' => max($sa['start']['y'], $sa['end']['y']));
        
        return $result;
    }

    /**
     * Check if line segments intersect
     * A line segment is array as :
     * ('start' => array('x' => 0, 'y' => 0),'end' => array('x' => 0, 'y' => 0));
     *
     * 
     * @param array $sa first line segment
     * @param array $sb second line segment
     * @return <code>true</code> if lines do intersect,
     *         <code>false</code> otherwise
     */
    public static function doLinesIntersect($sa, $sb) {
        $boxA = self::getBoundingBox($sa);
        $boxB = self::getBoundingBox($sb);
        return self::doBoundingBoxesIntersect($boxA, $boxB)
                && self::lineSegmentTouchesOrCrossesLine($sa, $sb)
                && self::lineSegmentTouchesOrCrossesLine($sb, $sa);
    }
}

?>