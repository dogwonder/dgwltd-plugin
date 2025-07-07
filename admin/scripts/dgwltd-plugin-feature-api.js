import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import { createBlock } from '@wordpress/blocks';
import { dispatch, select } from '@wordpress/data';

/**
 * DGW.ltd Feature API Integration
 * 
 * Provides client-side functionality for working with DGW.ltd blocks
 * via the WordPress Feature API
 */

class DGWFeatureAPI {
    constructor() {
        this.features = null;
        this.initialized = false;
    }

    /**
     * Initialize the Feature API
     */
    async init() {
        if ( typeof wp === 'undefined' || !wp.features ) {
            console.error( 'Feature API not available' );
            return false;
        }

        this.features = wp.features;
        this.initialized = true;
        
        // Log available features for debugging
        const availableFeatures = this.features.get().map( f => ({
            id: f.id,
            name: f.name,
            type: f.type,
            categories: f.categories
        }));
        
        console.log( 'DGW Feature API initialized. Available features:', availableFeatures );
        
        // Expose helper methods globally for easy access
        window.dgwFeatures = {
            insertBanner: this.insertBanner.bind(this),
            analyzeContent: this.analyzeContent.bind(this),
            insertBannerIntoCurrentPost: this.insertBannerIntoCurrentPost.bind(this),
            insertBannerIntoCurrentPage: this.insertBannerIntoCurrentPage.bind(this),
            getContentSuggestions: this.getContentSuggestions.bind(this),
            listFeatures: this.listFeatures.bind(this),
            createPageWithBanner: this.createPageWithBanner.bind(this),
            createPostWithBanner: this.createPostWithBanner.bind(this)
        };

        return true;
    }

    /**
     * Insert a banner block with full configuration
     */
    async insertBanner( config = {} ) {
        if ( !this.initialized ) {
            throw new Error( 'Feature API not initialized' );
        }

        const feature = this.features.find( 'dgwltd-plugin/insert-banner' );
        
        if ( !feature ) {
            throw new Error( 'Banner insertion feature not found' );
        }

        // Default configuration
        const defaultConfig = {
            title: 'New Banner',
            position: 'end',
            alignment: 'full',
            block_style: 'default',
            overlay_opacity: 70
        };

        const finalConfig = { ...defaultConfig, ...config };

        try {
            console.log( 'Inserting banner with config:', finalConfig );
            
            const result = await feature.run( finalConfig );
            
            if ( result.success ) {
                console.log( 'Banner inserted successfully:', result );
                
                // Show admin notice if in admin
                if ( window.wp && wp.data && wp.data.dispatch ) {
                    wp.data.dispatch( 'core/notices' ).createNotice(
                        'success',
                        __( 'Banner block inserted successfully!', 'dgwltd-plugin' ),
                        {
                            isDismissible: true,
                            actions: [
                                {
                                    label: __( 'Edit Post', 'dgwltd-plugin' ),
                                    url: result.edit_url
                                },
                                {
                                    label: __( 'View Post', 'dgwltd-plugin' ),
                                    url: result.view_url
                                }
                            ]
                        }
                    );
                }
            }
            
            return result;
            
        } catch ( error ) {
            console.error( 'Error inserting banner:', error );
            throw error;
        }
    }

    /**
     * Analyze content structure of a post
     */
    async analyzeContent( postId ) {
        if ( !this.initialized ) {
            throw new Error( 'Feature API not initialized' );
        }

        const feature = this.features.find( 'dgwltd-plugin/analyze-content' );
        
        if ( !feature ) {
            throw new Error( 'Content analysis feature not found' );
        }

        try {
            const analysis = await feature.run({ post_id: postId });
            console.log( 'Content analysis:', analysis );
            return analysis;
            
        } catch ( error ) {
            console.error( 'Error analyzing content:', error );
            throw error;
        }
    }

    /**
     * Insert banner into the currently open post (if in block editor)
     */
    async insertBannerIntoCurrentPost( config = {} ) {
        return await this.insertBannerIntoCurrentContent( { ...config, post_type: 'post' } );
    }

    /**
     * Insert banner into the currently open page (if in block editor)
     */
    async insertBannerIntoCurrentPage( config = {} ) {
        return await this.insertBannerIntoCurrentContent( { ...config, post_type: 'page' } );
    }

    /**
     * Insert banner into the currently open post/page (generic method)
     */
    async insertBannerIntoCurrentContent( config = {} ) {
        // Try to get current post ID from block editor
        let postId = null;
        let currentPostType = null;
        
        if ( window.wp && wp.data && wp.data.select ) {
            const currentPost = wp.data.select( 'core/editor' )?.getCurrentPost();
            if ( currentPost && currentPost.id ) {
                postId = currentPost.id;
                currentPostType = currentPost.type;
            }
        }

        // Fallback: try to get from URL params
        if ( !postId ) {
            const urlParams = new URLSearchParams( window.location.search );
            postId = urlParams.get( 'post' );
        }

        if ( !postId ) {
            const targetType = config.post_type || 'post';
            console.warn( `Could not determine current content ID, creating new ${targetType}` );
        }

        const bannerConfig = {
            ...config,
            post_id: postId ? parseInt( postId ) : undefined,
            post_type: config.post_type || currentPostType || 'post'
        };

        return await this.insertBanner( bannerConfig );
    }

    /**
     * Get content insertion suggestions for a post or page
     */
    async getContentSuggestions( postId ) {
        try {
            const analysis = await this.analyzeContent( postId );
            
            if ( analysis.suggestions && analysis.suggestions.length > 0 ) {
                const contentType = analysis.post_type === 'page' ? 'page' : 'post';
                console.log( `Found ${analysis.suggestions.length} insertion suggestions for ${contentType}:` );
                analysis.suggestions.forEach( (suggestion, index) => {
                    console.log( `${index + 1}. ${suggestion.reason} (${suggestion.position})` );
                });
            }
            
            return analysis.suggestions || [];
            
        } catch ( error ) {
            console.error( 'Error getting suggestions:', error );
            return [];
        }
    }

    /**
     * List all available DGW features
     */
    listFeatures() {
        if ( !this.initialized ) {
            console.error( 'Feature API not initialized' );
            return [];
        }

        const dgwFeatures = this.features.get().filter( f => 
            f.categories && f.categories.includes( 'dgwltd' )
        );

        console.log( 'DGW.ltd Features:' );
        dgwFeatures.forEach( feature => {
            console.log( `- ${feature.name} (${feature.id})` );
            console.log( `  Type: ${feature.type}` );
            console.log( `  Description: ${feature.description}` );
            console.log( `  Categories: ${feature.categories.join(', ')}` );
            console.log( '' );
        });

        return dgwFeatures;
    }

    /**
     * Quick banner insertion with smart defaults
     */
    async quickBanner( title, content = '', options = {} ) {
        const config = {
            title,
            content,
            position: 'end',
            ...options
        };

        return await this.insertBannerIntoCurrentPost( config );
    }

    /**
     * Insert banner with image
     */
    async insertBannerWithImage( title, content, imageUrl, options = {} ) {
        const config = {
            title,
            content,
            background_image_url: imageUrl,
            overlay_color: '#000000',
            overlay_opacity: 50,
            ...options
        };

        return await this.insertBannerIntoCurrentContent( config );
    }

    /**
     * Create a new page with a banner
     */
    async createPageWithBanner( title, content = '', options = {} ) {
        const config = {
            title,
            content,
            post_type: 'page',
            position: 'beginning',
            ...options
        };

        return await this.insertBanner( config );
    }

    /**
     * Create a new post with a banner
     */
    async createPostWithBanner( title, content = '', options = {} ) {
        const config = {
            title,
            content,
            post_type: 'post',
            position: 'beginning',
            ...options
        };

        return await this.insertBanner( config );
    }
}

// Initialize when DOM is ready
domReady( async () => {
    const dgwAPI = new DGWFeatureAPI();
    const initialized = await dgwAPI.init();
    
    if ( initialized ) {
        // Example usage in console:
        console.log( 'DGW Feature API ready! Try these in the console:' );
        console.log( '- dgwFeatures.listFeatures()' );
        console.log( '- dgwFeatures.insertBanner({ title: "My Banner", content: "Hello world!", post_type: "page" })' );
        console.log( '- dgwFeatures.analyzeContent(123)' );
        console.log( '- dgwFeatures.quickBanner("Quick Title", "Quick content")' );
        console.log( '- dgwFeatures.createPageWithBanner("New Page", "Page content")' );
        console.log( '- dgwFeatures.insertBannerIntoCurrentPage({ title: "Page Banner" })' );
        
        // Auto-run demo if in development mode
        if ( window.location.hostname.includes( 'dev' ) || window.location.hostname.includes( 'localhost' ) ) {
            console.log( 'Development mode detected - Feature API ready for testing' );
            
            // Uncomment to auto-insert a test banner:
            // setTimeout(() => {
            //     dgwFeatures.quickBanner('Development Test Banner', 'This banner was inserted automatically via the Feature API');
            // }, 2000);
        }
    }
} );