/// <reference types="cypress" />

// Welcome to Cypress!
//
// This spec file contains a variety of sample tests
// for a todo list app that are designed to demonstrate
// the power of writing tests in Cypress.
//
// To learn more about how Cypress works and
// what makes it such an awesome testing tool,
// please read our getting started guide:
// https://on.cypress.io/introduction-to-cypress

describe('homepage', () => {
    beforeEach(() => {
      // Cypress starts out with a blank slate for each test
      // so we must tell it to visit our website with the `cy.visit()` command.
      // Since we want to visit the same URL at the start of all our tests,
      // we include it in our beforeEach function so that it runs before each test
      cy.visit('https://127.0.0.1:8000/')
    })

    it('displays a link to create new campaign', () => {
      cy.contains('Créer une campagne')
    })

    it('displays form to create a campaign when clicking on the link', () => {
        cy.contains('Créer une campagne')
          .click()

        cy.get('#campaign-form')
      })
  })
