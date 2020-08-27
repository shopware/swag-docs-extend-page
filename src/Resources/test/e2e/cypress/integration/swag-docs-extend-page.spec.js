/*
// / <reference types="Cypress" />
*/
let productCount;

describe('Storefront: Extend Page', () => {
    before(() => {
        cy.searchViaAdminApi({
            endpoint: 'product',
            data: {
                field: 'active',
                value: true
            }
        }).then(({length}) => {
            productCount = length;
            cy.visit('/');
        });
    })

    it('should find the footer', () => {
        cy.contains('.demo-product-count', `This shop offers you ${productCount} products`)
    });
});
