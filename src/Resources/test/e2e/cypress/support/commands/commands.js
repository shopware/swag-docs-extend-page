Cypress.Commands.add('createViaAdminApi', (data) => {
    return cy.requestAdminApi(
        'POST',
        `/api/${Cypress.env('apiVersion')}/${data.endpoint}?response=true`,
        data
    ).then((responseData) => {
        return responseData;
    });
});
