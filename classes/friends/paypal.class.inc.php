<?php
/*******************************************************************************
 * Copyright (c) 2006 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Edouard Poitars (Eclipse Foundation) - Initial Implementation
 *******************************************************************************/

define('ECLIPSE_PAYPAL_MSG_SUCCESSFUL_UPDATE', 0);
define('ECLIPSE_PAYPAL_MSG_ERROR_UPDATE', 1);
define('ECLIPSE_PAYPAL_MSG_WARNING_DEBUG', 2);
define('ECLIPSE_PAYPAL_MSG_WARNING_SANDBOX', 3);
define('ECLIPSE_PAYPAL_MSG_SHOW_ALL_MODE', 4);
define('ECLIPSE_PAYPAL_MSG_IPN_VALID', 5);
define('ECLIPSE_PAYPAL_MSG_IPN_INVALID', 6);
define('PROXY', 'proxy.eclipse.org:9899');
define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('PAYPAL_SANDBOX_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYPAL_SANDBOX_AUTH_TOKEN', 'T-vs7NBkZlK-c10lW4aP9TGLOuhInTv2ZoGXGqBHp3CSZ6uEHiIN8lyaeq0');
define('PAYPAL_DONATION_EMAIL', 'donate@eclipse.org');
define('PAYPAL_SANDBOX_DONATION_EMAIL', 'business@eclipse.org');
define('PAYPAL_PURCHASE_CMD', '_xclick');
//define('PAYPAL_PURCHASE_CMD', '_donations');
// https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/
