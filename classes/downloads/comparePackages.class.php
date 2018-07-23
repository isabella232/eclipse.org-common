<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 * Christopher Guindon (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */

if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
  exit();
}

require_once (realpath(dirname(__FILE__) . "/../../system/app.class.php"));

/**
 * Compare packages
 *
 * Migrated from downloads.git
 *
 * @author chrisguindon
 */
class ComparePackages {
  private $App = NULL;
  protected $os_display = '';
  protected $display = '';
  private $platform = array();
  private $legend = array();
  private $devmode = FALSE;
  private $images = array(
    0 => '',
    1 => array(
      'included',
      'check.jpg'
    ),
    // 2 => array('partially included', 'checkpartial.jpg'),
    2 => array(
      'Included (with Source)',
      'checksource.jpg'
    )
  );
  protected $release = 'photon';
  private $header = array();
  private $header_strip = array(
    '(includes Incubating components)',
    'Eclipse IDE for ',
    'Eclipse for ',
    'Eclipse IDE for ',
    'Developers',
    'Software',
    'Eclipse'
  );
  private $row = array();
  private $path_xml_download = '/home/data/httpd/writable/community/';
  protected $features_accepted = array();
  protected $features_list = array();
  private $path_xml_packages = 'Win.xml';
  private $prefix_package = '';
  public $protocol = 'http';

  public function __construct($App = NULL) {
    if (!is_a($App, 'App')) {
      $App = new App();
    }
    $this->App = $App;

    if (!empty($this->App->devmode) && $this->App->devmode == TRUE) {
      $this->path_xml_download = $_SERVER['DOCUMENT_ROOT'] . '/downloads-xml/';
    }
    $this->protocol = $this->App->getHTTPPrefix();
    $this->setupPlatform($this->App);
    $this->features_accepted = $this->getFeaturesXml();

    $this->setPrefixPackage('release');
    $this->setRelease('latest');
  }

  public function setRelease($release) {
    $releases = array(
      'juno',
      'kepler',
      'luna',
      'mars',
      'neon',
      'oxygen',
      'photon',
      '2018-09',
      'latest'
    );
    if (in_array($release, $releases)) {
      $this->release = $release;
      $this->features_list = $this->getFeaturesList();
    }
  }

  public function getLegend() {
    foreach ($this->images as $id => $i) {
      $this->legend[] = $this->getLegendImage($id);
    }
    return implode(' ', $this->legend);
  }

  public function setPrefixPackage($prefix) {
    $this->prefix_package = $prefix . 'Cache';
    $this->prepareDownloads();
  }

  function getOs() {
    return $this->os_display;
  }

  protected function prepareDownloads() {
    $od = $this->os_display;
    if ($od == "linux" || $od == "linux-x64") {
      $this->display = "Linux";
      $this->updatePackages($this->prefix_package . "Linux.xml");
    }
    elseif ($od == "macosx" || $od == "cocoa64") {
      $this->display = "Mac OS X";
      $this->updatePackages($this->prefix_package . "Cocoa.xml");
    }
    elseif ($od == "carbon") {
      $this->display = "Mac OS X";
      $this->updatePackages($this->prefix_package . "Carbon.xml");
    }
    else {
      $this->display = "Windows";
      $this->updatePackages($this->prefix_package . "Win.xml");
    }
  }

  public function updatePackages($path) {
    $this->path_xml_packages = $path;
    $packages = simplexml_load_file($this->path_xml_download . $this->path_xml_packages, NULL, LIBXML_NOCDATA);
    $this->packages = $this->hackPackages($packages);
  }

  public function getPackages($package = NULL) {
    foreach ($this->packages as $p) {
      if ($p['package_bugzilla_id'] == $package) {
        return $p;
      }
    }
    return $this->packages;
  }

  public function getReadableFeature($id = NULL) {
    if (is_null($id) || $id == "" || empty($this->features_list[$id])) {
      return FALSE;
    }
    $this->features_list[$id]['name'] = (substr($this->features_list[$id]['name'], 0, 8) == 'Eclipse ') ? str_replace('Eclipse ', '', $this->features_list[$id]['name']) : $this->features_list[$id]['name'];
    if ($this->features_list[$id]['name'] == "%feature.label") {
      return FALSE;
    }
    return $this->features_list[$id];
  }

  public function output() {
    $this->getPackageData();
    ob_start();
    ?>
<table id="compareTable" class="table">
	<thead>
		<tr>
      <?php
    foreach ($this->header as $t) {
      print $t;
    }
    ?>
    </tr>
	</thead>
	<tbody>
      <?php
    $count = 0;
    foreach ($this->row as $t) {
      $count++;
      print '<tr id="row-' . $count . '">';
      foreach ($t as $c) {
        print $c;
      }
      print '</tr>';
    }
    ?>
    </tbody>
</table>
<?php
    return ob_get_clean();
  }

  private function getFeaturesXml() {
    // feature restriction
    $url = simplexml_load_file($this->path_xml_download . 'featuresRestriction.xml');
    $json = json_encode($url);
    return json_decode($json, TRUE);
  }

  private function getFeaturesList() {
    // readable features
    $url = $this->path_xml_download . 'features' . ucfirst($this->release) . '.json';
    $json = json_decode(file_get_contents($url), TRUE);
    return $this->renameFeatures($json);
  }

  private function hackPackages($p) {

    // adding RCP to all packages.
    foreach ($p->package as $f) {
      $ex = explode(',;', $f->features);
      if (!in_array('org.eclipse.rcp', $ex)) {
        $f->features = $f->features . 'org.eclipse.rcp,;';
      }

      // Scout
      if ($f['icon'] == 'http://www.eclipse.org/downloads/images/scout.jpg') {
        $f->features = $f->features . 'org.eclipse.scout.source,;';
      }

      if ($f['icon'] == 'http://www.eclipse.org/downloads/images/dsl-package.jpg') {
        $f->features = $f->features . 'org.eclipse.rcp.source,;org.eclipse.cvs.source,;org.eclipse.jdt.source,;org.eclipse.pde.source,;org.eclipse.xtend.sdk.source,;org.eclipse.xtext.sdk.source';
      }
      $count = 1;
      $f['icon'] = str_replace('http://www.eclipse.org', '', $f['icon'], $count);
      $f['id'] = $f['package_bugzilla_id'];
      $f['url'] = str_replace('http:', '', $f['url'], $count);
      $f['downloadurl'] = str_replace('http:', '', $f['downloadurl'], $count);
      $f['downloadurl64'] = str_replace('http:', '', $f['downloadurl64'], $count);
    }

    return $p;
  }

  private function renameFeatures($f) {
    return $f;
  }

  private function getPackageData() {
    $count = 0;

    $this->header[] = '<td class="col-' . $count . '"></td>';
    $this->row['radio'][] = '<td id="td_info" class="col-' . $count . '"><span>Select packages to compare</span></td>';

    // Setting up the first two rows
    foreach ($this->packages->package as $p) {
      $count++;
      $name = str_replace(' and ', '/', str_replace($this->header_strip, '', $p['name']));
      $this->row['radio'][] = '<td class="col-' . $count . '"><input type="checkbox" name="controls" id="controls-' . $count . '" value="col-' . $count . '" class="input-radio"/></td>';
      $this->header[] = '<td class="col-' . $count . '"><a href="' . $this->protocol . '://eclipse.org' . $p['url'] . '" title="' . $p['name'] . '"><img width="32" src="' . $p['icon'] . '"><br/>' . $name . '</a></td>';
    }

    // creating a row from each feature
    foreach ($this->features_accepted['item'] as $a) {
      $count = 0;
      $multif = explode(';', $a);
      $rfeatures = $this->getReadableFeature($multif[0]);
      if (empty($rfeatures)) {
        continue;
      }
      $this->row[$multif[0]][] = '<td class="td_feature-name col-' . $count . '"><span title="' . $rfeatures['description'] . '">' . $rfeatures['name'] . '</span></td>';
      foreach ($this->packages->package as $p) {
        $count++;
        $xfeatures = explode(",;", $p->features);
        foreach ($multif as $aa) {
          if (!empty($this->features_list[$aa]['id']) && in_array($this->features_list[$aa]['id'] . '.source', $xfeatures)) {
            $img = 2;
            break;
          }
          elseif (in_array($aa, $xfeatures)) {
            $img = 1;
            break;
            /*
             * }elseif ($a == 'org.eclipse.rcp'){
             * $img = 1;
             */
          }
          else {
            $img = 0;
          }
        }
        $this->row[$multif[0]][] = '<td class="col-' . $count . '">' . $this->getLegendImage($img) . '</td>';
      }
    }
  }

  private function getLegendImage($id = 0) {
    if (!$id) {
      return '&nbsp;';
    }

    $image_prefix = '/downloads/images/';
    return '<img width="16" src="' . $image_prefix . $this->images[$id][1] . '" alt="' . $this->images[$id][0] . '"><span class="check-description">' . $this->images[$id][0] . '</span>';
  }

  public function getRelease() {
    return $this->release;
  }

  private function setupPlatform($App) {
    $this->os_display = (!isset($_GET['osType'])) ? $this->App->getClientOS() : $_GET['osType'];
    if ($this->os_display == 'linux-x64') {
      $this->os_display = 'linux';
    }

    // default to win32 if $this->App->getClientOS() is returning something
    // strange.
    $platform = array(
      'win32',
      'linux',
      'macosx'
    );
    if (!in_array($this->os_display, $platform)) {
      $this->os_display = 'win32';
    }

    $this->platform['win32'] = array(
      'name' => 'Windows',
      'shortname' => 'Windows'
    );
    $this->platform['linux'] = array(
      'name' => 'Linux',
      'shortname' => 'Linux'
    );
    $this->platform['macosx'] = array(
      'name' => 'Mac OS X (Cocoa)',
      'shortname' => 'Mac OS X'
    );
  }
}
