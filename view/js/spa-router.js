// SPDX-FileCopyrightText: 2010-2026 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPLv3-or-later

/**
 * SPA Router for Friendica
 * Client-side routing implementation for /network, /display, /profile
 * Keeps footer static (for XMPP addon compatibility)
 * Vanilla-JS implementation - no external dependencies
 */

// ============================================
// FEATURE DETECTION
// ============================================

/**
 * Check if browser supports SPA features
 * @returns {boolean} True if History API and Fetch are supported
 */
const supportsSPA = window.history && window.history.pushState && window.fetch;

// ============================================
// GLOBAL STATE
// ============================================

// Track the final URL after redirects for display page detection
let lastFinalUrl = null;

// ============================================
// CONFIGURATION
// ============================================

const SPA_CONFIG = {
  enabled: true,
  excludedRoutes: [
    '/delegation',
  ],
  scrollToTopOnNavigate: true
};

// ============================================
// ROUTE HANDLING
// ============================================

/**
 * Check if a URL path is an SPA route
 * @param {string} path - The URL pathname
 * @returns {boolean} True if route should use SPA
 */
function isSPARoute(path) {
  return !SPA_CONFIG.excludedRoutes.some(route => {
    // Exact match or starts with route + /
    return path === route || path.startsWith(route + '/');
  });
}

/**
 * Check if a link is an internal link (same origin)
 * @param {HTMLAnchorElement} link - The anchor element
 * @returns {boolean} True if internal link
 */
function isInternalLink(link) {
  const href = link.getAttribute('href');
  if (!href) return false;

  // Skip anchor links, javascript: and mailto:
  if (href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:')) {
    return false;
  }
  
  // Check if same origin
  try {
    const url = new URL(href, window.location.href);
    return url.origin === window.location.origin;
  } catch (e) {
    return false;
  }
}

/**
 * Handle link clicks
 * @param {Event} e - Click event
 */
function handleLinkClick(e) {
  const link = e.target.closest('a');
  if (!link) {
    return;
  }
  
  let href = link.getAttribute('href');
  
  // Ignore anchor-only links (e.g., href="#" or href="#back-to-top")
  if (href && href.startsWith('#')) {
    console.debug('[SPA Router] Click: Anchor link, allowing default behavior');
    return;
  }
  
  // Ignore links that are meant to open modals (e.g., modal-open class)
  // These are handled by separate modal JavaScript handlers
  if (link.classList.contains('modal-open')) {
    console.debug('[SPA Router] Click: Modal link, allowing default/modal behavior');
    return;
  }

  // Ignore links with data-fancybox attribute (for Fancybox lightbox)
  // These are handled by Fancybox JavaScript
  // Push a state marker so we can detect back navigation from Fancybox
  if (link.hasAttribute('data-fancybox')) {
    console.debug('[SPA Router] Click: Fancybox link, pushing marker state and allowing default/fancybox behavior');
    history.pushState({ __fancyboxMarker: true }, '', window.location.href);
    return;
  }

  // Ignore links with inline event handlers (onclick, etc.)
  // These are handled by custom JavaScript and should not use SPA
  if (link.hasAttribute("onclick") || link.onclick || link.hasAttribute("data-spa-ignore")) {
    console.debug("[SPA Router] Click: Link has event handler or data-spa-ignore, allowing default behavior");
    return;
  }
  
  console.debug('[SPA Router] Click: Found anchor, href=', href);
  
  if (!isInternalLink(link)) {
    console.debug('[SPA Router] Click: Not an internal link, skipping');
    return;
  }

  if (!href.startsWith('http://') && !href.startsWith('https://') && !href.startsWith('/')) {
    href = '/' + href;
  }
  
  // Allow middle-click, ctrl-click, cmd-click to open in new tab
  if (e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey) {
    console.debug('[SPA Router] Click: Modified click (middle/ctrl/cmd/shift), skipping');
    return;
  }

  console.debug('[SPA Router] Click: Preventing default, navigating to', href);
  e.preventDefault();
  navigateTo(href);
}

/**
 * Set focus to content area for accessibility
 * Scrolls to top and sets focus on the content element
 * This is better for screen readers and keyboard navigation
 */
function scrollToTopInstant() {
  console.debug('[SPA Router] scrollToTopInstant: focusing on content element for accessibility');
  const contentElement = document.getElementById('content');
  
  // Save previous scroll behavior
  const html = document.documentElement;
  const previousBehavior = html.style.scrollBehavior;
  
  // Scroll to top instantly
  html.style.scrollBehavior = 'auto';
  window.scrollTo(0, 0);
  html.style.scrollBehavior = previousBehavior;
  
  // Set focus to content element for accessibility
  if (contentElement) {
    contentElement.setAttribute('tabindex', '-1'); // Make element focusable
    contentElement.focus();
  }
}

/**
 * Navigate to URL using SPA
 * @param {string} url - The URL to navigate to
 */
function navigateTo(url) {
  console.debug('[SPA Router] Navigate: url=', url);
  
  const path = new URL(url, window.location.href).pathname;
  console.debug('[SPA Router] Navigate: parsed path=', path);
  
  // Check if route is SPA-capable
  if (!isSPARoute(path)) {
    console.debug('[SPA Router] Navigate: Not an SPA route, falling back to full reload');
    window.location.href = url;
    return;
  }
  
  console.debug('[SPA Router] Navigate: SPA route detected, pushing state and loading content');
  
  // Dispatch event to pause live updates before navigation
  const beforeEvent = new CustomEvent('spa:beforeNavigate', {
    detail: { path: path, url: url }
  });
  window.dispatchEvent(beforeEvent);
  
  // Update History API
  history.pushState({ path, spa: true, __friendicaSPA: true }, '', url);

  // Load content
  loadContent(url);
}

// ============================================
// CONTENT LOADING
// ============================================

/**
 * Show timeout modal overlay
 * Displays a modal dialog that can be clicked away
 */
function showTimeoutModal() {
  console.debug('[SPA Router] showTimeoutModal: Displaying timeout overlay');
  
  hideLoading();
  
  // Check if modal already exists
  if (document.getElementById('spa-timeout-modal')) {
    console.debug('[SPA Router] showTimeoutModal: Modal already exists');
    return;
  }
  
  // Get translated texts from PHP
  const title = window.spaErrorTexts.timeout;
  const message = window.spaErrorTexts.timeout_message;
  const closeText = window.spaErrorTexts.close;
  
  // Create modal overlay
  const modal = document.createElement('div');
  modal.id = 'spa-timeout-modal';
  modal.className = 'spa-modal-overlay';
  
  // Create modal content
  const content = document.createElement('div');
  content.className = 'spa-modal-content';
  
  // Create heading
  const heading = document.createElement('h2');
  heading.textContent = title;
  
  // Create message paragraph
  const messageElement = document.createElement('p');
  messageElement.textContent = message;
  
  // Create close button
  const closeButton = document.createElement('button');
  closeButton.textContent = closeText;
  closeButton.className = 'btn btn-primary spa-modal-close-btn';
  closeButton.onclick = function() {
    dismissTimeoutModal();
  };
  
  content.appendChild(heading);
  content.appendChild(messageElement);
  content.appendChild(closeButton);
  
  modal.appendChild(content);
  
  // Close modal when clicking on overlay (outside content)
  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      dismissTimeoutModal();
    }
  });
  
  // Add modal to body
  document.body.appendChild(modal);
  
  // Also listen for Escape key
  const escapeHandler = function(e) {
    if (e.key === 'Escape' || e.keyCode === 27) {
      dismissTimeoutModal();
    }
  };
  document.addEventListener('keydown', escapeHandler);
  
  // Store reference to clean up
  modal._escapeHandler = escapeHandler;
}

/**
 * Dismiss the timeout modal
 */
function dismissTimeoutModal() {
  const modal = document.getElementById('spa-timeout-modal');
  if (modal) {
    if (modal._escapeHandler) {
      document.removeEventListener('keydown', modal._escapeHandler);
    }
    modal.remove();
    console.debug('[SPA Router] dismissTimeoutModal: Modal removed');
  }
}

/**
 * Handle HTTP error responses
 * @param {number} status - HTTP status code
 * @param {string} url - The original request URL
 */
function handleHttpError(status, url) {
  console.debug('[SPA Router] handleHttpError: status=', status, 'url=', url);
  
  // 401: redirect to login
  if (status === 401) {
    console.debug('[SPA Router] handleHttpError: 401 Unauthorized - redirecting to login');
    window.location.href = '/login?return_path=' + encodeURIComponent(url);
  }
}

/**
 * Load content via AJAX
 * @param {string} url - The URL to load
 */
function loadContent(url) {
  console.debug('[SPA Router] LoadContent: url=', url);
  
  // Use normal URL without SPA parameter - server returns full HTML
  const fetchUrl = new URL(url, window.location.href);
  console.debug('[SPA Router] LoadContent: fetching URL=', fetchUrl.toString());
  
  // Track the final URL after all redirects
  let finalUrl = fetchUrl.toString();

  showFetching();
  fetch(fetchUrl, {
    headers: {
      'Accept': 'text/html'
    },
    credentials: 'include' // Send cookies for same-origin requests
  })
  .then(async (response) => {
    
    console.debug('[SPA Router] LoadContent: Response received, status=', response.status, 'response.url=', response.url);
    
    // Check Content-Type header - only text/html is allowed for SPA
    const contentType = response.headers.get('Content-Type') || response.headers.get('content-type') || '';
    if (!contentType.includes('text/html')) {
      console.debug('[SPA Router] LoadContent: Invalid Content-Type:', contentType, '- falling back to full reload');
      window.location.href = url;
      return null;
    }
    
    // Get the final URL after any redirects - with automatic following, response.url contains it
    if (response.url && response.url !== fetchUrl.toString()) {
      finalUrl = response.url;
      console.debug('[SPA Router] LoadContent: Final URL after redirects:', finalUrl);
    }
    
    // Special handling for 401 and 504
    if (response.status === 401) {
      console.debug('[SPA Router] LoadContent: 401 Unauthorized - redirecting to login');
      handleHttpError(401, url);
      return null;
    }
    
    if (response.status === 504) {
      console.debug('[SPA Router] LoadContent: 504 Gateway Timeout - showing modal');
      showTimeoutModal();
      return null;
    }
    
    // For all other responses (including other HTTP errors like 404, 500, etc.)
    // load the server's error page content via SPA
    showReceiving();
    
    const html = await response.text();
    
    console.debug('[SPA Router] LoadContent: HTML received, type:', typeof html, 'length:', html ? html.length : 0, 'finalUrl:', finalUrl);
    
    showProcessing();
    
    // Validate that html is a string
    if (typeof html !== 'string') {
      console.error('[SPA Router] LoadContent: html is not a string! type:', typeof html, 'value:', html);
      throw new Error('Response body is not a string');
    }
    
    // Update history with the final URL if there were redirects
    if (finalUrl !== fetchUrl.toString()) {
      console.debug('[SPA Router] LoadContent: Updating history to final URL:', finalUrl);
      history.replaceState({ path: new URL(finalUrl).pathname, spa: true, __friendicaSPA: true }, '', finalUrl);
    }
    
    // Replace content of the three main containers
    // Pass the final URL to detect display pages after redirects
    replaceContainerContent(html, finalUrl);
    hideLoading();
    
    console.debug('[SPA Router] LoadContent: Process completed successfully');
    return html;
  })
  .catch(error => {
    hideLoading();
    console.error('[SPA Router] Error loading content:', error);
    console.error('[SPA Router] Error stack:', error.stack);
    console.error('[SPA Router] Error name:', error.name);
    console.error('[SPA Router] Error message:', error.message);
    console.error('[SPA Router] Error details:', {
      url: url,
      fetchUrl: fetchUrl.toString(),
      finalUrl: finalUrl,
      error: error
    });
    
    // Handle timeout errors with modal - only for server 504, not for client timeout
    // Client timeout (AbortError) just dismisses the delay modal, does not show timeout modal
    if (error.name === 'AbortError') {
      console.debug('[SPA Router] LoadContent: AbortError (client timeout) - dismissing delay modal only');
      // Don't show timeout modal for client timeout, only dismiss delay modal
    } else if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
      // Network error - fall back to full reload
      console.debug('[SPA Router] LoadContent: Network error - falling back to reload');
      window.location.href = url;
    } else {
      // Fallback: Full page reload for other errors
      console.debug('[SPA Router] Falling back to full page reload for URL:', url);
      window.location.href = url;
    }
  });
}

// ============================================
// CONTAINER CONTENT REPLACEMENT
// ============================================

/**
 * Remove all tooltip elements to prevent ghost tooltips after SPA navigation
 * Targets common tooltip classes and dynamically created tooltip elements
 */
function cleanupTooltips() {
  const tooltipSelectors = [
    '.tooltip',                    // Standard tooltip class
    '[class*="tooltip"]',          // Classes containing "tooltip"
    '.ui-tooltip',                // jQuery UI tooltips
    '.popover',                   // Bootstrap popovers
    '[role="tooltip"]',           // ARIA role tooltips
    '.fancybox-wrap',             // Fancybox
    '.colorbox',                  // Colorbox
    '.jGrowl',                    // jGrowl notifications
    '.panel'                      // Friendica permission panel (from lockview)
  ];

  tooltipSelectors.forEach(selector => {
    const elements = document.querySelectorAll(selector);
    // Convert NodeList to Array for compatibility with older browsers
    const elementsArray = Array.prototype.slice.call(elements);
    elementsArray.forEach(el => {
      console.debug('[SPA Router] Cleanup: Removing tooltip element:', selector);
      el.remove();
    });
  });
}

/**
 * Replace content of the three main containers: nav#topbar-first, div#topbar-second, and main
 * This approach preserves the DOM structure while updating the content
 * @param {string} html - New content HTML (may be full document or fragment)
 * @param {string} finalUrl - The final URL after following redirects (optional)
 */
function replaceContainerContent(html, finalUrl = null) {
  console.debug('[SPA Router] ReplaceContent: Processing HTML, finalUrl:', finalUrl);
  
  // Clean up any existing tooltips first to prevent ghost elements
  cleanupTooltips();
  
  // Validate html is a string
  if (typeof html !== 'string') {
    console.error('[SPA Router] ReplaceContent: html is not a string! type:', typeof html, 'value:', html);
    html = '';
  }
  
  // Store the final URL for potential redirect handling
  if (finalUrl) {
    lastFinalUrl = finalUrl;
    console.debug('[SPA Router] ReplaceContent: Set lastFinalUrl to:', lastFinalUrl);
  } else {
    lastFinalUrl = window.location.href;
    console.debug('[SPA Router] ReplaceContent: Set lastFinalUrl to window.location.href:', lastFinalUrl);
  }
  
  // Extract title from full HTML before body extraction
  const titleTempDiv = document.createElement('div');
  titleTempDiv.innerHTML = html;
  const titleElement = titleTempDiv.querySelector('title');
  if (titleElement) {
    console.debug('[SPA Router] ReplaceContent: Setting title to', titleElement.textContent);
    document.title = titleElement.textContent;
    originalTitle = document.title;
  }

  // If response is a full HTML document, extract only the body content
  if (html && html.includes && html.includes('<body')) {
    console.debug('[SPA Router] ReplaceContent: Extracting body content');
    const bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
    if (bodyMatch) {
      html = bodyMatch[1];
    }
  }
  
  // Extract inline scripts - separate global (var/let/const/infinite_scroll) from body scripts
  const tempDiv = document.createElement('div');
  tempDiv.innerHTML = html;

  const globalScripts = [];
  const bodyScripts = [];

  const scripts = tempDiv.querySelectorAll('script:not([src])');
  Array.prototype.slice.call(scripts).forEach((script) => {
    const scriptContent = script.textContent.trim();
    if (!scriptContent) return;

    if (scriptContent.startsWith('var ') ||
        scriptContent.startsWith('let ') ||
        scriptContent.startsWith('const ') ||
        scriptContent.trim().startsWith('infinite_scroll =') ||
        scriptContent.includes('infinite_scroll =')) {
      globalScripts.push(scriptContent);
    } else {
      // All other inline scripts (including initWidget) are body scripts
      bodyScripts.push(scriptContent);
    }
  });

  // Execute global scripts immediately (variable declarations)
  globalScripts.forEach((scriptContent, index) => {
    try {
      console.debug('[SPA Router] Executing global script #' + index + ': ' + scriptContent.substring(0, 150));
      const scriptEl = document.createElement('script');
      scriptEl.textContent = scriptContent;
      document.head.appendChild(scriptEl);
      document.head.removeChild(scriptEl);
    } catch (e) {
      console.error('[SPA Router] Error executing global script #' + index + ':', e);
    }
  });

  // Store body scripts to execute after DOM insertion
  window.__spa_bodyScripts = bodyScripts;
  
  // Extract head elements (link, script, style) and move them to document head
  const headElements = tempDiv.querySelectorAll('link, script, style');
  console.debug('[SPA Router] ReplaceContent: Found', headElements.length, 'head elements');
  // Convert NodeList to Array for compatibility with older browsers
  const headElementsArray = Array.prototype.slice.call(headElements);
  headElementsArray.forEach(el => {
    const existing = document.head.querySelector(
      `${el.tagName.toLowerCase()}[${el.src ? 'src' : el.href ? 'href' : 'data-spa-processed'}="${el.src || el.href || ''}"]`
    );
    if (!existing && !el.getAttribute('data-spa-processed')) {
      console.debug('[SPA Router] ReplaceContent: Adding head element:', el.tagName, el.src || el.href || '(inline)');
      const clone = el.cloneNode(true);
      clone.setAttribute('data-spa-processed', 'true');
      document.head.appendChild(clone);
    }
  });
  
  // Define the three main containers to update
  const containers = [
    { selector: 'nav#topbar-first', newContent: tempDiv.querySelector('nav#topbar-first') },
    { selector: 'div#topbar-second', newContent: tempDiv.querySelector('div#topbar-second') },
    { selector: 'main', newContent: tempDiv.querySelector('main') }
  ];
  
  console.debug('[SPA Router] ReplaceContent: Checking containers...');
  
  // Replace content of each container
  containers.forEach(({ selector, newContent }) => {
    if (newContent) {
      console.debug('[SPA Router] ReplaceContent: Found new content for', selector);
      
      // Debug: Show content preview for topbar-second
      if (selector === 'div#topbar-second') {
        const preview = newContent.innerHTML.substring(0, 300).replace(/\n/g, ' ').replace(/\s+/g, ' ');
        console.debug('[SPA Router] div#topbar-second content:', 
                    'length=', newContent.innerHTML.length, 
                    'preview="', preview, '"');
      }
      
      const oldContainer = document.querySelector(selector);
      if (oldContainer) {
        console.debug('[SPA Router] ReplaceContent: Found old container for', selector, '- replacing innerHTML');

        // Check if this is a display page with GUID - if so, don't scroll to top
        // Use finalUrl if provided (for redirects), otherwise use window.location.pathname
        const effectivePath = finalUrl ? new URL(finalUrl).pathname : window.location.pathname;
        const isDisplayPageWithGuid = effectivePath.includes('/display/') && 
          (() => {
            const pathParts = effectivePath.split('/');
            const displayIndex = pathParts.indexOf('display');
            return displayIndex >= 0 && displayIndex + 1 < pathParts.length && pathParts[displayIndex + 1];
          })();
        
        console.debug('[SPA Router] ReplaceContent: effectivePath:', effectivePath, 'isDisplayPageWithGuid:', isDisplayPageWithGuid);

        if (selector === 'main' && SPA_CONFIG.scrollToTopOnNavigate && !isDisplayPageWithGuid) {
          console.debug('[SPA Router] ReplaceContent: Scrolling to top (not display page with GUID)');
          oldContainer.style.visibility = 'hidden';
          try {
            scrollToTopInstant();
            oldContainer.innerHTML = newContent.innerHTML;
          } finally {
            oldContainer.style.visibility = '';
          }
        } else if (selector === 'main' && isDisplayPageWithGuid) {
          console.debug('[SPA Router] ReplaceContent: NOT scrolling to top (display page with GUID)');
          // Replace the inner HTML of the container
          oldContainer.innerHTML = newContent.innerHTML;
        } else {
          // Replace the inner HTML of the container
          oldContainer.innerHTML = newContent.innerHTML;
        }
      } else {
        console.warn('[SPA Router] ReplaceContent: Old container NOT found for', selector);
      }
    } else {
      console.warn('[SPA Router] ReplaceContent: New content NOT found for', selector);
    }
  });
  
  // Handle scroll-loader element separately (it might be outside the main container)
  const scrollLoaderNew = tempDiv.querySelector('#scroll-loader');
  console.debug('[SPA Router] ReplaceContent: scrollLoaderNew found:', !!scrollLoaderNew);
  if (scrollLoaderNew) {
    const scrollLoaderOld = document.getElementById('scroll-loader');
    console.debug('[SPA Router] ReplaceContent: scrollLoaderOld found:', !!scrollLoaderOld);
    if (scrollLoaderOld) {
      console.debug('[SPA Router] ReplaceContent: Replacing scroll-loader element');
      scrollLoaderOld.innerHTML = scrollLoaderNew.innerHTML;
    } else {
      console.debug('[SPA Router] ReplaceContent: Adding scroll-loader element');
      document.body.appendChild(scrollLoaderNew.cloneNode(true));
    }
  } else {
    console.debug('[SPA Router] ReplaceContent: No scroll-loader element found in new content');
  }
  
  console.debug('[SPA Router] ReplaceContent: Calling reinitializeDynamicContent');
  
  // Re-initialize dynamic content (event listeners, etc.)
  reinitializeDynamicContent();
}

// ============================================
// EVENT LISTENERS & REINITIALIZATION
// ============================================

/**
 * Re-initialize dynamic content after SPA navigation
 * This is important for elements that need event listeners
 */
function reinitializeDynamicContent() {
  // Re-attach click handlers to new links
  // Convert NodeList to Array for compatibility with older browsers
  const links = document.querySelectorAll('a');
  Array.prototype.slice.call(links).forEach(link => {
    // Links already have the handler from event delegation, but
    // we need to ensure any new dynamic content works
  });
  
  // Execute body scripts (including widget init scripts) after DOM insertion
  if (window.__spa_bodyScripts && window.__spa_bodyScripts.length > 0) {
    console.debug('[SPA Router] reinitializeDynamicContent: Executing ' + window.__spa_bodyScripts.length + ' body scripts');
    window.__spa_bodyScripts.forEach((scriptContent, index) => {
      try {
        console.debug('[SPA Router] reinitializeDynamicContent: Executing body script #' + index + ': ' + scriptContent.substring(0, 150));
        const scriptEl = document.createElement('script');
        scriptEl.textContent = scriptContent;
        document.head.appendChild(scriptEl);
        document.head.removeChild(scriptEl);
        console.debug('[SPA Router] Executed body script #' + index);
      } catch (e) {
        console.error('[SPA Router] Error executing body script #' + index + ':', e);
      }
    });
    // Clear the stored scripts after execution
    window.__spa_bodyScripts = [];
  }
  
  // Dispatch custom events for other scripts to hook into
  const spaNavigateEvent = new CustomEvent('spa:navigate', {
    detail: { path: window.location.pathname }
  });
  window.dispatchEvent(spaNavigateEvent);
  
  // Trigger theme reinitialization for elements like #back-to-top
  const themeReloadEvent = new CustomEvent('theme:reload');
  window.dispatchEvent(themeReloadEvent);
  
  // Trigger infinite scroll reinitialization for network pages
  const initInfiniteScrollEvent = new CustomEvent('spa:initInfiniteScroll');
  window.dispatchEvent(initInfiniteScrollEvent);
  
  // Trigger NavUpdate to check for unread posts immediately after SPA navigation
  if (typeof NavUpdate === 'function') {
    console.debug('[SPA Router] Calling NavUpdate after navigation');
    NavUpdate();
  }
}

/**
 * Handle initial page load
 */
function handleInitialLoad() {
  // Initial content is already there, just initialize
  const event = new CustomEvent('spa:ready', {
    detail: { path: window.location.pathname }
  });
  window.dispatchEvent(event);
}

/**
 * Handle browser back/forward navigation
 * @param {Event} e - Popstate event
 */
function handlePopState(e) {
  console.debug('[SPA Router] PopState: state=', e.state, 'hash=', window.location.hash);
  
  // Check if this is a Fancybox marker state (created when clicking a Fancybox link)
  if (e.state && e.state.__fancyboxMarker) {
    console.debug('[SPA Router] PopState: Fancybox marker state detected, ignoring to let Fancybox handle navigation');
    return;
  }
  
  // Check if Fancybox lightbox is currently open (via various possible DOM elements)
  // Fancybox 2: .fancybox-wrap
  // Fancybox 3: .fancybox-container, .fancybox-bg, .fancybox-is-open
  const fancyboxSelectors = [
    '.fancybox-wrap',
    '.fancybox-container', 
    '.fancybox-bg',
    '.fancybox-is-open',
    '.fancybox-stage',
    '[class*="fancybox"]'
  ];
  
  const fancyboxActive = fancyboxSelectors.some(selector => document.querySelector(selector) !== null);
  
  // Also check if there are any open modal/dialog elements that might be from Fancybox
  const modalBackdrop = document.querySelector('.modal-backdrop, .fb-backdrop, [class*="backdrop"]');
  
  if (fancyboxActive || modalBackdrop !== null) {
    console.debug('[SPA Router] PopState: Fancybox or modal is active, ignoring to let it handle closing');
    return;
  }
  
  if (e.state && e.state.spa && e.state.__friendicaSPA) {
    console.debug('[SPA Router] PopState: SPA navigation detected, loading', window.location.href);
    
    // Dispatch event to pause live updates before popstate navigation
    const beforeEvent = new CustomEvent('spa:beforeNavigate', {
      detail: { path: window.location.pathname, url: window.location.href }
    });
    window.dispatchEvent(beforeEvent);
    
    loadContent(window.location.href);
  } else {
    console.debug('[SPA Router] PopState: Not an SPA state or not from our router, ignoring');
  }
}

// ============================================
// MAIN INITIALIZATION
// ============================================

/**
 * Initialize SPA Router
 */
function initSPARouter() {
  console.debug('[SPA Router] Initializing... supportsSPA=', supportsSPA, 'enabled=', SPA_CONFIG.enabled);
  
  if (!supportsSPA || !SPA_CONFIG.enabled) {
    console.debug('[SPA Router] Not supported or disabled');
    return;
  }
  
  // Initial load handler
  window.addEventListener('DOMContentLoaded', handleInitialLoad);
  
  // Intercept link clicks (using event delegation)
  document.addEventListener('click', handleLinkClick);
  
  // Browser navigation (back/forward)
  window.addEventListener('popstate', handlePopState);
  
  console.debug('[SPA Router] Initialized successfully');
}

// ============================================
// FEATURE DETECTION & FALLBACK
// ============================================

// Check if browser supports required features
if (!supportsSPA) {
  console.debug('[SPA Router] Browser does not support SPA features. Using fallback.');
} else {
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSPARouter);
  } else {
    initSPARouter();
  }
}

// Export for testing/module use
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    initSPARouter,
    isSPARoute,
    isInternalLink,
    supportsSPA,
    SPA_CONFIG
  };
}
