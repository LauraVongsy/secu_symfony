describe('Test utilisateur', () => {
    it('Ajout dun compte utilisateur', () => {
        cy.visit('https://securite.adrardev.fr/register')
        cy.get('#register_firstname').clear().type("Laura")
        cy.get('#register_name').clear().type("vng2")
        cy.get('#register_email').clear().type("laura@test.com")
        cy.get('#register_password_first').clear().type("1234")
        cy.get('#register_password_second').clear().type("1234")
        cy.get('#register_Ajouter').click()
        cy.get('strong').should("contain", "Le compte laura@test.com a été ajouté en BDD")
    })

})
describe('Test utilisateur', () => {
    it('Ajout dun compte utilisateur', () => {
        cy.visit('https://securite.adrardev.fr/register')
        cy.get('#register_firstname').clear().type("Laura")
        cy.get('#register_name').clear().type("vng2")
        cy.get('#register_email').clear().type("laura@test.com")
        cy.get('#register_password_first').clear().type("1234")
        cy.get('#register_password_second').clear().type("1234")
        cy.get('#register_Ajouter').click()
        cy.get('strong').should("contain", "Le compte laura@test.com existe déja")
    })

})