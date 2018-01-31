fos.Router.setData({"base_url":"","routes":{"member_member_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["text","\/member\/member\/list-part"]],"defaults":{"anchor":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"member_subscription_fees_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["variable","\/","\\d+","subHistId"],["text","\/members\/fees\/list_part"]],"defaults":[],"requirements":{"subHistId":"\\d+"},"hosttokens":[],"methods":[],"schemes":[]},"member_status_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["text","\/member\/status\/list-part"]],"defaults":{"anchor":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"member_status_save_json":{"tokens":[["text","\/member\/status\/json\/save"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"member_status_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/member\/status\/delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_payment_type_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["text","\/subscription\/payment\/list-part"]],"defaults":{"anchor":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_payment_type_save_json":{"tokens":[["text","\/subscription\/paymentType\/json\/save"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_payment_type_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/subscription\/paymentType\/delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_periodicity_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/subscription\/\/periodicity_delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":["POST"],"schemes":[]},"subscription_periodicity_list_part":{"tokens":[["text","\/subscription\/periodicity\/list-part"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_subscription_list_part":{"tokens":[["text","\/subscription\/subscription\/list-part"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"subscription_subscription_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/subscription\/delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":["POST"],"schemes":[]},"user_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["text","\/user\/list-part"]],"defaults":{"anchor":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"user_group_list_part":{"tokens":[["variable","\/","[^\/]++","anchor"],["text","\/user\/group\/list-part"]],"defaults":{"anchor":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"user_group_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/user\/group\/delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"bazinga_jstranslation_js":{"tokens":[["variable",".","js|json","_format"],["variable","\/","[\\w]+","domain"],["text","\/translations"]],"defaults":{"domain":"messages","_format":"js"},"requirements":{"_format":"js|json","domain":"[\\w]+"},"hosttokens":[],"methods":["GET"],"schemes":[]}},"prefix":"","host":"localhost","scheme":"http"});