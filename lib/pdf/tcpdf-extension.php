<?php

/**
 * Extended TCPDF class to add custom functionality
 */

/**
 * Extending TCPDF
 *
 * Note: If you are combining PDF files, use this class.
 */
class MYPDF extends FPDI {

    /**
     * Storage for files to be concatenated
     * @files array
     */
    var $files = array();

    /**
     * Custom header
     */
    public function Header() {
        global $post;

        if ($this->page == 1) {
            $image_file = get_stylesheet_directory_uri() . '/lib/pdf/images/logo_300dpi.jpg';
            $this->Image($image_file, 18, 5, 75, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false, false, '');
            $this->Cell(0, 15, '', 0, 1, 'C', false, '', 0, false, 'T', 'T');
        } else {
            $image_file = get_stylesheet_directory_uri() . '/lib/pdf/images/logo_300dpi.jpg';
            $title = get_the_title($post->ID);

            ob_start();
            include(STYLESHEETPATH . '/lib/pdf/templates/pdf-header.php');
            $header_mup = ob_get_clean();

            $this->writeHTML($header_mup, true, false, false, false, '');

            $this->SetFont('freesans', '', 9, '', false);
            $header_content = $this->getAliasRightShift() . 'Page ' . rtrim($this->getAliasNumPage()) . ' of ' . rtrim($this->getAliasNbPages());
            $this->writeHTMLCell(200.5, 15, 0, 20, $header_content, 0, 1, false, true, 'R', false);
        }
    }

    /**
     * Custom footer
     */
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('freesans', '', 9);
        $footer_content = '&copy; ' . date('Y') . ' The Leiden Collection';
        $this->writeHTMLCell(0, 15, '', '', $footer_content, 0, 0, false, false, 'R', true);
    }

    /**
     * Multirow function
     * @param type $left
     * @param type $right
     */
    public function MultiRow($left, $right, $dims) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
        //writeHTMLCell ($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
        //vars
        $page_break_checker = 0;

        if ($dims['debug']) {
            echo '<pre>' . print_r($left, true) . '</pre>';
            echo '<pre>' . print_r($right, true) . '</pre>';
            echo '<pre>' . print_r('Left page: ' . $dims['left_page'], true) . '</pre>';
            echo '<pre>' . print_r('Left Y: ' . $dims['left_y'], true) . '</pre>';
        }

        if ($dims['left_page'] === 0) {
            $this->setPage($this->getPage());
        } else {
            try {
                $this->setPage($dims['left_page']);
            } catch (Exception $e) {
                $this->setPage($dims['left_page'] - 1);
                $this->AddPage();
                $this->setPage($dims['left_page']);
            }
        }

        if ($dims['left_y'] === 0) {
            $left_y = $this->GetY();
        } else {
            $left_y = $dims['left_y'];
        }

        if ($dims['left_x'] === 0) {
            $left_x = $this->GetX();
            $dims['left_x'] = $left_x;
        } else {
            $left_x = $dims['left_x'];
        }

        // write the left cell
        if ($dims['content_full_width']) {
            $this->writeHTMLCell(180, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
        } else if ($dims['content_maybe_full_width']) {

            if ($dims['right_y'] + $dims['next_fig_h'] < $left_y) {
                $this->writeHTMLCell(180, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
            }
        } else {
            $this->writeHTMLCell(125, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
        }

        $dims['left_page'] = $this->getPage();
        $left_end_y = $this->GetY();
        $dims['left_y'] = $left_end_y;

        if ($dims['debug']) {
            echo '<pre>' . print_r('Right page: ' . $dims['right_page'], true) . '</pre>';
            echo '<pre>' . print_r('Right Y: ' . $dims['right_y'], true) . '</pre>';
        }

        if ($dims['right_page'] === 0) {
            $this->setPage($this->getPage());
        } else {
            try {
                $this->setPage($dims['right_page']);
            } catch (Exception $e) {
                $this->setPage($dims['right_page'] - 1);
                $this->AddPage();
                $this->setPage($dims['right_page']);
            }
        }

        if ($dims['right_y'] === 0) {
            $right_y = $left_y + 14;
        } else {
            $right_y = $dims['right_y'];
        }

        if ($dims['right_x'] === 0) {
            $right_x = $this->GetX() + 8;
            $dims['right_x'] = $right_x;
        } else {
            $right_x = $dims['right_x'];
        }

        if ($dims['debug']) {
            echo '<pre>' . print_r('content_full_width: ' . $dims['content_full_width'], true) . '</pre>';
        }

        // write the right cell
        if ($dims['content_full_width']) {
            $this->writeHTMLCell(0, '', $right_x, $right_y, $right, 0, 1, false, true, '', false);
        } else {
            $this->writeHTMLCell(47, '', $right_x, $right_y, $right, 0, 1, false, true, '', false);
        }

        $right_h = $this->getLastH();
        $dims['right_page'] = $this->getPage();
        $dims['right_y'] = $this->GetY();

        if ($dims['next_fig']) {

            $pdf2 = clone $this;
            $pdf2->AddPage();
            $pdf2->writeHTMLCell(47, '', '', $dims['y_init'], $dims['next_fig'], 0, 1, false, true, '', false);
            $fig_adder = $pdf2->getLastH();
            $dims['next_fig_height'] = $fig_adder;

            $page_break_checker = $this->GetY() + $fig_adder;

            /*if ($dims['right_y'] < $left_end_y) {
                $dims['content_maybe_full_width'] = true;
            }*/

            if ($dims['debug']) {
                echo '<pre>' . print_r('Looking forward: ' . $page_break_checker, true) . '</pre>';
            }
        } else if (!$dims['content_full_width']) {

            if ($dims['right_y'] < $left_end_y) {
                $dims['content_full_width'] = true;
            }
        }

        //if we're really close to the bottom of the page, let's move on to the next page
        if ($page_break_checker > $dims['content_h']) {

            $dims['right_page'] = $dims['right_page'] + 1;
            $dims['right_y'] = $dims['init_y'];
        }

        return $dims;
    }

    /**
     * Concatenation: setting files
     * @param type $files
     */
    public function setFiles($files) {
        $this->files = $files;
    }

    /**
     * Concatenate PDF
     */
    public function concat() {
        foreach ($this->files AS $file) {
            $pagecount = $this->setSourceFile($file);
            for ($i = 1; $i <= $pagecount; $i++) {
                $tplidx = $this->ImportPage($i);
                $s = $this->getTemplatesize($tplidx);
                $this->AddPage('P', array($s['w'], $s['h']));
                $this->useTemplate($tplidx);
            }
        }
    }

    /**
     * Exception handling
     * @param type $msg
     * @throws Exception
     */
    public function Error($msg) {
        throw new Exception('TCPDF Error: ' . $msg);
    }

}

/**
 * Extending TCPDF
 *
 * This class does not output page headers.
 *
 * Note: Do not use for combining files. Only use for files that will
 * be combined later.
 */
class ConcatPDF extends FPDI {

    /**
     * Custom header
     */
    public function Header() {
        $this->SetFont('freesans', '', 9, '', false);
        $header_content = '';
        $this->writeHTMLCell(200.5, 15, 0, 20, $header_content, 0, 1, false, true, 'R', false);
    }

    /**
     * Custom footer
     */
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('freesans', '', 9);
        $footer_content = '';
        $this->writeHTMLCell(0, 15, '', '', $footer_content, 0, 0, false, false, 'R', true);
    }

    /**
     * Multirow function
     * @param type $left
     * @param type $right
     */
    public function MultiRow($left, $right, $dims) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)
        //writeHTMLCell ($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=false, $reseth=true, $align='', $autopadding=true)
        //vars
        $page_break_checker = 0;

        if ($dims['debug']) {
            echo '<pre>' . print_r($left, true) . '</pre>';
            echo '<pre>' . print_r($right, true) . '</pre>';
            echo '<pre>' . print_r('Left page: ' . $dims['left_page'], true) . '</pre>';
            echo '<pre>' . print_r('Left Y: ' . $dims['left_y'], true) . '</pre>';
        }

        if ($dims['left_page'] === 0) {
            $this->setPage($this->getPage());
        } else {
            try {
                $this->setPage($dims['left_page']);
            } catch (Exception $e) {
                $this->setPage($dims['left_page'] - 1);
                $this->AddPage();
                $this->setPage($dims['left_page']);
            }
        }

        if ($dims['left_y'] === 0) {
            $left_y = $this->GetY();
        } else {
            $left_y = $dims['left_y'];
        }

        if ($dims['left_x'] === 0) {
            $left_x = $this->GetX();
            $dims['left_x'] = $left_x;
        } else {
            $left_x = $dims['left_x'];
        }

        // write the left cell
        if ($dims['content_full_width']) {
            $this->writeHTMLCell(180, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
        } else if ($dims['content_maybe_full_width']) {

            if ($dims['right_y'] + $dims['next_fig_h'] < $left_y) {
                $this->writeHTMLCell(180, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
            }
        } else {
            $this->writeHTMLCell(125, '', $left_x, $left_y, $left, 0, 2, false, true, '', false);
        }

        $dims['left_page'] = $this->getPage();
        $left_end_y = $this->GetY();
        $dims['left_y'] = $left_end_y;

        if ($dims['debug']) {
            echo '<pre>' . print_r('Right page: ' . $dims['right_page'], true) . '</pre>';
            echo '<pre>' . print_r('Right Y: ' . $dims['right_y'], true) . '</pre>';
        }

        if ($dims['right_page'] === 0) {
            $this->setPage($this->getPage());
        } else {
            try {
                $this->setPage($dims['right_page']);
            } catch (Exception $e) {
                $this->setPage($dims['right_page'] - 1);
                $this->AddPage();
                $this->setPage($dims['right_page']);
            }
        }

        if ($dims['right_y'] === 0) {
            $right_y = $left_y + 14;
        } else {
            $right_y = $dims['right_y'];
        }

        if ($dims['right_x'] === 0) {
            $right_x = $this->GetX() + 8;
            $dims['right_x'] = $right_x;
        } else {
            $right_x = $dims['right_x'];
        }

        if ($dims['debug']) {
            echo '<pre>' . print_r('content_full_width: ' . $dims['content_full_width'], true) . '</pre>';
        }

        // write the right cell
        if ($dims['content_full_width']) {
            $this->writeHTMLCell(0, '', $right_x, $right_y, $right, 0, 1, false, true, '', false);
        } else {
            $this->writeHTMLCell(47, '', $right_x, $right_y, $right, 0, 1, false, true, '', false);
        }

        $right_h = $this->getLastH();
        $dims['right_page'] = $this->getPage();
        $dims['right_y'] = $this->GetY();

        if ($dims['next_fig']) {

            $pdf2 = clone $this;
            $pdf2->AddPage();
            $pdf2->writeHTMLCell(47, '', '', $dims['y_init'], $dims['next_fig'], 0, 1, false, true, '', false);
            $fig_adder = $pdf2->getLastH();
            $dims['next_fig_height'] = $fig_adder;

            $page_break_checker = $this->GetY() + $fig_adder;

            /*if ($dims['right_y'] < $left_end_y) {
                $dims['content_maybe_full_width'] = true;
            }*/

            if ($dims['debug']) {
                echo '<pre>' . print_r('Looking forward: ' . $page_break_checker, true) . '</pre>';
            }
        } else if (!$dims['content_full_width']) {

            if ($dims['right_y'] < $left_end_y) {
                $dims['content_full_width'] = true;
            }
        }

        //if we're really close to the bottom of the page, let's move on to the next page
        if ($page_break_checker > $dims['content_h']) {

            $dims['right_page'] = $dims['right_page'] + 1;
            $dims['right_y'] = $dims['init_y'];
        }

        return $dims;
    }

    /**
     * Exception handling
     * @param type $msg
     * @throws Exception
     */
    public function Error($msg) {
        throw new Exception('TCPDF Error: ' . $msg);
    }

}
