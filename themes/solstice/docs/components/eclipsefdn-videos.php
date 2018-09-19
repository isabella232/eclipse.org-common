<?php
/**
 * Copyright (c) 2018 Eclipse Foundation.
 *
 * This program and the accompanying materials are made
 * available under the terms of the Eclipse Public License 2.0
 * which is available at https://www.eclipse.org/legal/epl-2.0/
 *
 * Contributors:
 *   Eric Poirier (Eclipse Foundation) - Initial implementation
 *
 * SPDX-License-Identifier: EPL-2.0
 */
?>

<h2 id="embedding-youtube-videos">Embedding Youtube videos</h2>

            <div class="row">
              <div class="col-sm-12">
                <h3>What is the Embedding Youtube videos plugin?</h3>
                <p>By inserting an <code>&lt;a&gt;</code> tag containing a video link in your HTML page,
                the EclipseFdn Videos plugin will convert that tag into the iframe video if the user
                agreed to use cookies.</p>
                <p><strong>Note:</strong> This plugin only supports Youtube videos for now.</p>
                <h3>Usage</h3>
                <p><strong>With Solstice</strong></p>
                <pre>&lt;head&gt;
  &lt;script&gt;
    // Use defaults
    eclipseFdnVideos.replace();

    // Customize
    eclipseFdnVideos.replace({
      selector: ".eclipsefdn-video",
      resolution: "16by9",
      cookie: {
        name: "eclipse_cookieconsent_status",
        value: "allow"
      }
    });
  &lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;a class="eclipsefdn-video" href="https://www.youtube.com/watch?v=cnSMhgKApOg"&gt;&lt;/a&gt;

  &lt;!--
  &lt;a&gt; will be replaced with:
  &lt;div class="eclipsefdn-video embed-responsive embed-responsive-16by9" style="height:312.1875px;"&gt;&lt;iframe src="https://www.youtube.com/embed/cnSMhgKApOg"&gt;&lt;/iframe>&lt;/div&gt;
  --&gt;
&lt;/body&gt;
</pre>
                <p><strong>Without Solstice</strong></p>
                <p>If you are not using the Eclipse Foundation look and feel, you can still load our Embedding Youtube videos plugin like the following example:</p>
                <pre>&lt;head&gt;
  &lt;script src="//www.eclipse.org/eclipse.org-common/themes/solstice/public/javascript/eclipsefdn.videos.min.js"&gt;&lt;/script&gt;
  &lt;link href="//www.eclipse.org/eclipse.org-common/themes/solstice/public/stylesheets/eclipsefdn-video.min.css" rel="stylesheet" type="text/css"&gt;

  &lt;script&gt;
    // Use defaults
    eclipseFdnVideos.replace();

    // Customize
    eclipseFdnVideos.replace({
      selector: ".eclipsefdn-video",
      resolution: "16by9",
      cookie: {
        name: "eclipse_cookieconsent_status",
        value: "allow"
      }
    });
  &lt;/script&gt;
&lt;/head&gt;
&lt;body&gt;
  &lt;a class="eclipsefdn-video" href="https://www.youtube.com/watch?v=cnSMhgKApOg"&gt;&lt;/a&gt;

  &lt;!--
  &lt;a&gt; will be replaced with:
  &lt;div class="eclipsefdn-video embed-responsive embed-responsive-16by9" style="height:312.1875px;"&gt;&lt;iframe src="https://www.youtube.com/embed/cnSMhgKApOg"&gt;&lt;/iframe>&lt;/div&gt;
  --&gt;
&lt;/body&gt;
</pre>
                <p><strong>Parameters</strong></p>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Description</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><code>selector</code> (optional)</td>
                      <td>String</td>
                      <td>By default the class <strong>"eclipsefdn-video"</strong> is being used but you can specify your own selector.</td>
                    </tr>
                    <tr>
                      <td><code>resolution</code> (optional)</td>
                      <td>String</td>
                      <td>By default the resolution of the video is <strong>16by9</strong> but you can also choose to use the <strong>4by3</strong> resolution.
                      Note that only these two resolutons are accepted.</td>
                    </tr>
                    <tr>
                      <td><code>cookie</code> (optional)</td>
                      <td>Object</td>
                      <td>By default we are using the cookie name <strong>"eclipse_cookieconsent_status"</strong> and value
                      <strong>"allow"</strong> which enables the plugin to replace the link by the iframe video only if
                      users have given consent to use cookies. But you can choose to use your own cookie name and values.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-12">
                <h3>Example:</h3>

                <a class="eclipsefdn-video" href="https://www.youtube.com/watch?v=cnSMhgKApOg"></a>
              </div>
            </div>