Feature: Test get kraken Assets request

  Scenario: Check positive test for get Assets
    When I send GET request to "public/Assets"
    Then response status code should be 200
      And JSON response body should be like:
        """
          {git s
              "error": [],
              "result": {
                  "BCH": {
                      "aclass": "currency",
                      "altname": "BCH",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "DASH": {
                      "aclass": "currency",
                      "altname": "DASH",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "EOS": {
                      "aclass": "currency",
                      "altname": "EOS",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "GNO": {
                      "aclass": "currency",
                      "altname": "GNO",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "KFEE": {
                      "aclass": "currency",
                      "altname": "FEE",
                      "decimals": 2,
                      "display_decimals": 2
                  },
                  "USDT": {
                      "aclass": "currency",
                      "altname": "USDT",
                      "decimals": 8,
                      "display_decimals": 4
                  },
                  "XDAO": {
                      "aclass": "currency",
                      "altname": "DAO",
                      "decimals": 10,
                      "display_decimals": 3
                  },
                  "XETC": {
                      "aclass": "currency",
                      "altname": "ETC",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XETH": {
                      "aclass": "currency",
                      "altname": "ETH",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XICN": {
                      "aclass": "currency",
                      "altname": "ICN",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XLTC": {
                      "aclass": "currency",
                      "altname": "LTC",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XMLN": {
                      "aclass": "currency",
                      "altname": "MLN",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XNMC": {
                      "aclass": "currency",
                      "altname": "NMC",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XREP": {
                      "aclass": "currency",
                      "altname": "REP",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XXBT": {
                      "aclass": "currency",
                      "altname": "XBT",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XXDG": {
                      "aclass": "currency",
                      "altname": "XDG",
                      "decimals": 8,
                      "display_decimals": 2
                  },
                  "XXLM": {
                      "aclass": "currency",
                      "altname": "XLM",
                      "decimals": 8,
                      "display_decimals": 5
                  },
                  "XXMR": {
                      "aclass": "currency",
                      "altname": "XMR",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "XXRP": {
                      "aclass": "currency",
                      "altname": "XRP",
                      "decimals": 8,
                      "display_decimals": 5
                  },
                  "XXVN": {
                      "aclass": "currency",
                      "altname": "XVN",
                      "decimals": 4,
                      "display_decimals": 2
                  },
                  "XZEC": {
                      "aclass": "currency",
                      "altname": "ZEC",
                      "decimals": 10,
                      "display_decimals": 5
                  },
                  "ZCAD": {
                      "aclass": "currency",
                      "altname": "CAD",
                      "decimals": 4,
                      "display_decimals": 2
                  },
                  "ZEUR": {
                      "aclass": "currency",
                      "altname": "EUR",
                      "decimals": 4,
                      "display_decimals": 2
                  },
                  "ZGBP": {
                      "aclass": "currency",
                      "altname": "GBP",
                      "decimals": 4,
                      "display_decimals": 2
                  },
                  "ZJPY": {
                      "aclass": "currency",
                      "altname": "JPY",
                      "decimals": 2,
                      "display_decimals": 0
                  },
                  "ZKRW": {
                      "aclass": "currency",
                      "altname": "KRW",
                      "decimals": 2,
                      "display_decimals": 0
                  },
                  "ZUSD": {
                      "aclass": "currency",
                      "altname": "USD",
                      "decimals": 4,
                      "display_decimals": 2
                  }
              }
          }
        """

  Scenario: Check not existing url
    When I send GET request to "public/Assets/123"
    Then response status code should be 404