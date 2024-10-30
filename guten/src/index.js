import './index.scss';
import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import ChildPagesCardAdmin from './components/childpagescardadmin';

domReady( () => {
    const root = createRoot(
        document.getElementById( 'child-pages-card-settings' )
    );

    root.render( <ChildPagesCardAdmin /> );
} );
